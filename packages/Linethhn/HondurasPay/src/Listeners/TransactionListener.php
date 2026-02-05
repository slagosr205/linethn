<?php

namespace Linethhn\HondurasPay\Listeners;

use Illuminate\Support\Facades\Log;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Linethhn\HondurasPay\Repositories\HondurasPayTransactionRepository;

class TransactionListener
{
    public function __construct(
        protected HondurasPayTransactionRepository $transactionRepository,
        protected OrderTransactionRepository $orderTransactionRepository
    ) {
    }

    /**
     * Handle order creation event.
     */
    public function orderCreated($order): void
    {
        if ($order->payment->method !== 'honduras_pay') {
            return;
        }

        Log::info('HondurasPay: Order created #' . $order->id);
    }

    /**
     * Handle invoice save event - record transaction in Bagisto's order_transactions table.
     */
    public function saveTransaction($invoice): void
    {
        if ($invoice->order->payment->method !== 'honduras_pay') {
            return;
        }

        // Find the HondurasPay transaction for this order
        $transactions = $this->transactionRepository->getByOrderId($invoice->order->id);
        $completedTransaction = $transactions->firstWhere('status', 'completed');

        if (! $completedTransaction) {
            return;
        }

        // Check if already recorded in Bagisto's order_transactions
        $existingTransaction = $this->orderTransactionRepository
            ->findWhere([
                'order_id'       => $invoice->order->id,
                'transaction_id' => $completedTransaction->transaction_id,
            ])
            ->first();

        if ($existingTransaction) {
            return;
        }

        try {
            $this->orderTransactionRepository->create([
                'transaction_id' => $completedTransaction->transaction_id,
                'status'         => $completedTransaction->status,
                'type'           => 'payment',
                'amount'         => $completedTransaction->amount,
                'payment_method' => 'honduras_pay',
                'order_id'       => $invoice->order->id,
                'invoice_id'     => $invoice->id,
                'data'           => json_encode([
                    'gateway_code'       => $completedTransaction->gateway_code,
                    'authorization_code' => $completedTransaction->authorization_code,
                    'reference_number'   => $completedTransaction->reference_number,
                    'bank_name'          => $completedTransaction->bank_name,
                    'card_brand'         => $completedTransaction->card_brand,
                    'card_last_four'     => $completedTransaction->card_last_four,
                    'paid_at'            => $completedTransaction->paid_at?->toISOString(),
                ]),
            ]);

            Log::info('HondurasPay: Transaction recorded for order #' . $invoice->order->id);
        } catch (\Exception $e) {
            Log::error('HondurasPay: Error recording transaction', [
                'order_id' => $invoice->order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }
}
