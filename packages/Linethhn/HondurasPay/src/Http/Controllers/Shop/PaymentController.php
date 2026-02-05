<?php

namespace Linethhn\HondurasPay\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Linethhn\HondurasPay\Services\PaymentGatewayService;
use Linethhn\HondurasPay\Repositories\HondurasPayTransactionRepository;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentGatewayService $gatewayService,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected HondurasPayTransactionRepository $transactionRepository
    ) {
    }

    /**
     * Redirect to payment gateway.
     */
    public function redirect()
    {
        $cart = Cart::getCart();

        if (! $cart) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'No se encontró el carrito de compras.');
        }

        $orderData = [
            'order_id'       => session('last_order_id') ?? $cart->id,
            'grand_total'    => $cart->grand_total,
            'customer_name'  => $cart->customer_first_name . ' ' . $cart->customer_last_name,
            'customer_email' => $cart->customer_email,
            'customer_phone' => $cart->billing_address->phone ?? '',
        ];

        $result = $this->gatewayService->initiatePayment($orderData);

        if ($result['success'] && ! empty($result['redirect_url'])) {
            return redirect()->away($result['redirect_url']);
        }

        if ($result['success'] && ! empty($result['form_data'])) {
            return view('honduras-pay::shop.checkout.redirect-form', [
                'formData' => $result['form_data'],
                'actionUrl' => $result['redirect_url'],
            ]);
        }

        return redirect()->route('shop.checkout.cart.index')
            ->with('error', $result['message'] ?? 'Error al procesar el pago.');
    }

    /**
     * Show the payment form for custom/direct payments.
     */
    public function showForm(Request $request)
    {
        $token = $request->get('token');

        if (! $token) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Token de pago inválido.');
        }

        try {
            $paymentData = json_decode(decrypt(base64_decode($token)), true);
        } catch (\Exception $e) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Token de pago expirado o inválido.');
        }

        return view('honduras-pay::shop.checkout.payment-form', [
            'paymentData' => $paymentData,
            'token'       => $token,
        ]);
    }

    /**
     * Process the direct payment form submission.
     */
    public function processForm(Request $request)
    {
        $request->validate([
            'token'       => 'required|string',
            'card_number' => 'required|string|min:13|max:19',
            'card_expiry' => 'required|string|regex:/^\d{2}\/\d{2}$/',
            'card_cvv'    => 'required|string|min:3|max:4',
            'card_name'   => 'required|string|max:100',
        ]);

        try {
            $paymentData = json_decode(decrypt(base64_decode($request->token)), true);
        } catch (\Exception $e) {
            return back()->with('error', 'Token de pago expirado.');
        }

        // In a real implementation, this would send the card data to the gateway
        // Never store raw card data - only tokenized or masked values
        $transaction = $this->transactionRepository->findByTransactionId($paymentData['order_id'] ?? '');

        if (! $transaction) {
            return redirect()->route('shop.checkout.cart.index')
                ->with('error', 'Transacción no encontrada.');
        }

        // Simulate/process payment through the gateway
        $result = $this->gatewayService->verifyPayment([
            'status'         => 'completed',
            'transaction_id' => uniqid('HP_'),
            'amount'         => $paymentData['amount'],
        ]);

        if ($result['verified']) {
            $this->transactionRepository->markCompleted($transaction->id, [
                'transaction_id'     => $result['transaction_id'],
                'authorization_code' => $result['authorization_code'],
                'card_last_four'     => substr($request->card_number, -4),
                'gateway_response'   => $result,
            ]);

            $this->completeOrder($transaction->order_id);

            return redirect()->route('shop.checkout.onepage.success')
                ->with('order_id', $transaction->order_id);
        }

        $this->transactionRepository->markFailed($transaction->id, $result['message']);

        return back()->with('error', $result['message'] ?? 'El pago fue rechazado. Intente con otra tarjeta.');
    }

    /**
     * Handle successful payment callback.
     */
    public function success(Request $request)
    {
        $data = $request->all();

        Log::info('HondurasPay Success Callback', $data);

        $result = $this->gatewayService->verifyPayment($data);

        if ($result['verified']) {
            $transaction = $this->transactionRepository->findByTransactionId(
                $result['transaction_id']
            );

            if ($transaction) {
                $this->transactionRepository->markCompleted($transaction->id, [
                    'authorization_code' => $result['authorization_code'],
                    'gateway_response'   => $data,
                ]);

                $this->completeOrder($transaction->order_id);

                session()->flash('order_id', $transaction->order_id);
            }

            Cart::deActivateCart();

            return redirect()->route('shop.checkout.onepage.success');
        }

        return redirect()->route('shop.checkout.cart.index')
            ->with('error', 'No se pudo verificar el pago. Contacte al soporte.');
    }

    /**
     * Handle cancelled payment.
     */
    public function cancel(Request $request)
    {
        Log::info('HondurasPay Payment Cancelled', $request->all());

        return redirect()->route('shop.checkout.cart.index')
            ->with('warning', 'El pago fue cancelado. Puede intentar nuevamente.');
    }

    /**
     * Handle gateway webhook/IPN notifications.
     */
    public function webhook(Request $request)
    {
        $data = $request->all();

        Log::info('HondurasPay Webhook Received', $data);

        // Verify webhook signature if configured
        $webhookSecret = core()->getConfigData('sales.payment_methods.honduras_pay.webhook_secret');

        if ($webhookSecret) {
            $signature = $request->header('X-Webhook-Signature')
                ?? $request->header('X-Signature');

            if (! $this->verifyWebhookSignature($data, $signature, $webhookSecret)) {
                Log::warning('HondurasPay Webhook: Invalid signature');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $result = $this->gatewayService->verifyPayment($data);

        $transactionId = $data['transaction_id'] ?? $data['transactionid'] ?? $data['id'] ?? null;

        if ($transactionId) {
            $transaction = $this->transactionRepository->findByTransactionId($transactionId);

            if ($transaction) {
                if ($result['verified']) {
                    $this->transactionRepository->markCompleted($transaction->id, [
                        'authorization_code' => $result['authorization_code'],
                        'gateway_response'   => $data,
                    ]);

                    $this->completeOrder($transaction->order_id);
                } else {
                    $this->transactionRepository->markFailed(
                        $transaction->id,
                        $result['message'] ?? 'Payment failed via webhook'
                    );
                }
            }
        }

        return response()->json(['status' => 'received']);
    }

    /**
     * Complete the order after successful payment.
     */
    protected function completeOrder(int $orderId): void
    {
        $order = $this->orderRepository->find($orderId);

        if (! $order) {
            return;
        }

        // Generate invoice if configured
        $generateInvoice = core()->getConfigData('sales.payment_methods.honduras_pay.generate_invoice');

        if ($generateInvoice) {
            $invoiceStatus = core()->getConfigData('sales.payment_methods.honduras_pay.invoice_status') ?? 'paid';

            try {
                $invoiceData = [
                    'order_id' => $order->id,
                ];

                foreach ($order->items as $item) {
                    $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
                }

                if ($order->canInvoice()) {
                    $this->invoiceRepository->create(array_merge($invoiceData, [
                        'invoice' => array_merge($invoiceData['invoice'] ?? [], [
                            'status' => $invoiceStatus,
                        ]),
                    ]));
                }
            } catch (\Exception $e) {
                Log::error('HondurasPay: Error generating invoice for order #' . $order->id, [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Update order status
        $orderStatus = core()->getConfigData('sales.payment_methods.honduras_pay.order_status') ?? 'processing';
        $order->update(['status' => $orderStatus]);
    }

    /**
     * Verify webhook signature.
     */
    protected function verifyWebhookSignature(array $data, ?string $signature, string $secret): bool
    {
        if (! $signature) {
            return false;
        }

        $computedSignature = hash_hmac('sha256', json_encode($data), $secret);

        return hash_equals($computedSignature, $signature);
    }
}
