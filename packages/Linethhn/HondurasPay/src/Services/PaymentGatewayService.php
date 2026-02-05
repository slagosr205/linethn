<?php

namespace Linethhn\HondurasPay\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Linethhn\HondurasPay\Payment\HondurasPay;
use Linethhn\HondurasPay\Repositories\HondurasPayTransactionRepository;
use Linethhn\HondurasPay\Models\HondurasPayTransaction;

class PaymentGatewayService
{
    public function __construct(
        protected HondurasPay $paymentMethod,
        protected HondurasPayTransactionRepository $transactionRepository
    ) {
    }

    /**
     * Initiate a payment with the configured gateway.
     */
    public function initiatePayment(array $orderData): array
    {
        $payload = $this->paymentMethod->buildPaymentPayload($orderData);
        $apiUrl = $this->paymentMethod->getApiBaseUrl();
        $provider = $this->paymentMethod->getGatewayProvider();

        // Create a pending transaction record
        $transaction = $this->transactionRepository->create([
            'gateway_code'     => $provider,
            'order_id'         => $orderData['order_id'] ?? null,
            'status'           => HondurasPayTransaction::STATUS_PENDING,
            'type'             => 'payment',
            'amount'           => $orderData['grand_total'],
            'currency'         => $this->paymentMethod->getPaymentCurrency(),
            'ip_address'       => request()->ip(),
            'metadata'         => [
                'customer_email' => $orderData['customer_email'] ?? '',
                'customer_name'  => $orderData['customer_name'] ?? '',
            ],
        ]);

        try {
            $response = $this->sendPaymentRequest($apiUrl, $payload, $provider);

            if ($response['success']) {
                $this->transactionRepository->update([
                    'transaction_id'   => $response['transaction_id'] ?? null,
                    'status'           => HondurasPayTransaction::STATUS_PROCESSING,
                    'gateway_response' => $response,
                ], $transaction->id);

                return [
                    'success'        => true,
                    'transaction_id' => $transaction->id,
                    'redirect_url'   => $response['redirect_url'] ?? null,
                    'form_data'      => $response['form_data'] ?? null,
                    'message'        => $response['message'] ?? 'Redirigiendo a pasarela de pago...',
                ];
            }

            $this->transactionRepository->markFailed(
                $transaction->id,
                $response['message'] ?? 'Error desconocido del gateway'
            );

            return [
                'success' => false,
                'message' => $response['message'] ?? 'Error al procesar el pago',
            ];
        } catch (\Exception $e) {
            Log::error('HondurasPay Gateway Error: ' . $e->getMessage(), [
                'order_id'   => $orderData['order_id'] ?? null,
                'provider'   => $provider,
                'exception'  => $e->getTraceAsString(),
            ]);

            $this->transactionRepository->markFailed(
                $transaction->id,
                $e->getMessage()
            );

            return [
                'success' => false,
                'message' => 'Error de conexión con la pasarela de pago. Intente nuevamente.',
            ];
        }
    }

    /**
     * Send the payment request to the gateway.
     */
    protected function sendPaymentRequest(string $url, array $payload, string $provider): array
    {
        if (empty($url)) {
            return [
                'success'      => true,
                'redirect_url' => route('honduras-pay.form', [
                    'token' => $this->generatePaymentToken($payload),
                ]),
                'message' => 'Redirigiendo al formulario de pago...',
            ];
        }

        $headers = $this->buildHeaders($provider);

        $response = Http::withHeaders($headers)
            ->timeout(30)
            ->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();

            return [
                'success'        => true,
                'transaction_id' => $data['transaction_id'] ?? $data['id'] ?? null,
                'redirect_url'   => $data['redirect_url'] ?? $data['payment_url'] ?? null,
                'form_data'      => $data['form_data'] ?? null,
                'message'        => $data['message'] ?? 'Pago iniciado exitosamente',
                'raw'            => $data,
            ];
        }

        return [
            'success' => false,
            'message' => $response->json('message') ?? 'Error del gateway: ' . $response->status(),
            'raw'     => $response->json(),
        ];
    }

    /**
     * Build request headers for the gateway.
     */
    protected function buildHeaders(string $provider): array
    {
        $apiKey = $this->paymentMethod->getApiKey();
        $apiSecret = $this->paymentMethod->getApiSecret();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        switch ($provider) {
            case 'bac_credomatic':
                $headers['Authorization'] = 'Basic ' . base64_encode($apiKey . ':' . $apiSecret);
                break;

            case 'tigo_money':
            case 'tengo':
                $headers['Authorization'] = 'Bearer ' . $apiKey;
                $headers['X-Api-Key'] = $apiSecret;
                break;

            default:
                $headers['X-Api-Key'] = $apiKey;
                $headers['X-Api-Secret'] = $apiSecret;
                break;
        }

        return $headers;
    }

    /**
     * Generate a secure payment token for form-based payments.
     */
    protected function generatePaymentToken(array $payload): string
    {
        $data = json_encode([
            'order_id' => $payload['order_id'] ?? $payload['orderid'] ?? $payload['reference'] ?? '',
            'amount'   => $payload['amount'] ?? 0,
            'time'     => time(),
        ]);

        return base64_encode(encrypt($data));
    }

    /**
     * Verify a payment response/callback from the gateway.
     */
    public function verifyPayment(array $responseData): array
    {
        $provider = $this->paymentMethod->getGatewayProvider();

        switch ($provider) {
            case 'bac_credomatic':
                return $this->verifyBacPayment($responseData);
            default:
                return $this->verifyGenericPayment($responseData);
        }
    }

    /**
     * Verify BAC Credomatic payment response.
     */
    protected function verifyBacPayment(array $data): array
    {
        $responseCode = $data['response'] ?? $data['response_code'] ?? '';

        return [
            'verified'           => in_array($responseCode, ['1', '100']),
            'transaction_id'     => $data['transactionid'] ?? $data['transaction_id'] ?? null,
            'authorization_code' => $data['authcode'] ?? $data['authorization'] ?? null,
            'status'             => $responseCode === '1' ? 'completed' : 'failed',
            'message'            => $data['responsetext'] ?? $data['message'] ?? '',
            'amount'             => $data['amount'] ?? 0,
        ];
    }

    /**
     * Verify generic payment response.
     */
    protected function verifyGenericPayment(array $data): array
    {
        $status = $data['status'] ?? $data['payment_status'] ?? '';
        $isSuccess = in_array(strtolower($status), ['completed', 'approved', 'success', 'paid']);

        return [
            'verified'           => $isSuccess,
            'transaction_id'     => $data['transaction_id'] ?? $data['id'] ?? null,
            'authorization_code' => $data['authorization_code'] ?? $data['auth_code'] ?? null,
            'status'             => $isSuccess ? 'completed' : 'failed',
            'message'            => $data['message'] ?? $data['status_message'] ?? '',
            'amount'             => $data['amount'] ?? $data['total'] ?? 0,
        ];
    }

    /**
     * Process a refund.
     */
    public function processRefund(int $transactionId, float $amount = null): array
    {
        $transaction = $this->transactionRepository->find($transactionId);

        if (! $transaction || ! $transaction->canRefund()) {
            return [
                'success' => false,
                'message' => 'Esta transacción no puede ser reembolsada.',
            ];
        }

        $refundAmount = $amount ?? $transaction->amount;
        $apiUrl = $this->paymentMethod->getApiBaseUrl();
        $provider = $this->paymentMethod->getGatewayProvider();

        try {
            $refundPayload = [
                'transaction_id' => $transaction->transaction_id,
                'amount'         => $refundAmount,
                'currency'       => $transaction->currency,
                'type'           => 'refund',
            ];

            $headers = $this->buildHeaders($provider);

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->post($apiUrl . '/refund', $refundPayload);

            if ($response->successful()) {
                $this->transactionRepository->update([
                    'status'           => HondurasPayTransaction::STATUS_REFUNDED,
                    'refunded_at'      => now(),
                    'gateway_response' => array_merge(
                        $transaction->gateway_response ?? [],
                        ['refund' => $response->json()]
                    ),
                ], $transactionId);

                return [
                    'success' => true,
                    'message' => 'Reembolso procesado exitosamente.',
                ];
            }

            return [
                'success' => false,
                'message' => 'Error al procesar el reembolso: ' . ($response->json('message') ?? 'Error desconocido'),
            ];
        } catch (\Exception $e) {
            Log::error('HondurasPay Refund Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error de conexión al procesar el reembolso.',
            ];
        }
    }
}
