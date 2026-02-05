<?php

namespace Linethhn\HondurasPay\Repositories;

use Webkul\Core\Eloquent\Repository;

class HondurasPayTransactionRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Linethhn\HondurasPay\Contracts\HondurasPayTransaction';
    }

    /**
     * Get transactions by order ID.
     */
    public function getByOrderId(int $orderId)
    {
        return $this->model->where('order_id', $orderId)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get transaction by gateway transaction ID.
     */
    public function findByTransactionId(string $transactionId)
    {
        return $this->model->where('transaction_id', $transactionId)->first();
    }

    /**
     * Mark transaction as completed.
     */
    public function markCompleted(int $id, array $data = []): mixed
    {
        return $this->update(array_merge($data, [
            'status'  => 'completed',
            'paid_at' => now(),
        ]), $id);
    }

    /**
     * Mark transaction as failed.
     */
    public function markFailed(int $id, string $errorMessage = ''): mixed
    {
        return $this->update([
            'status'        => 'failed',
            'error_message' => $errorMessage,
        ], $id);
    }

    /**
     * Get transaction statistics.
     */
    public function getStatistics(string $period = 'month')
    {
        $query = $this->model->where('status', 'completed');

        switch ($period) {
            case 'today':
                $query->whereDate('paid_at', today());
                break;
            case 'week':
                $query->whereBetween('paid_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year);
                break;
            case 'year':
                $query->whereYear('paid_at', now()->year);
                break;
        }

        return [
            'total_amount'       => $query->sum('amount'),
            'total_transactions' => $query->count(),
            'average_amount'     => $query->avg('amount'),
        ];
    }
}
