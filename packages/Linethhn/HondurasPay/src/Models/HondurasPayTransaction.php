<?php

namespace Linethhn\HondurasPay\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Models\OrderProxy;
use Webkul\Sales\Models\InvoiceProxy;
use Linethhn\HondurasPay\Contracts\HondurasPayTransaction as HondurasPayTransactionContract;

class HondurasPayTransaction extends Model implements HondurasPayTransactionContract
{
    protected $table = 'honduras_pay_transactions';

    protected $fillable = [
        'transaction_id',
        'gateway_code',
        'order_id',
        'invoice_id',
        'status',
        'type',
        'amount',
        'currency',
        'authorization_code',
        'reference_number',
        'card_last_four',
        'card_brand',
        'bank_name',
        'error_message',
        'gateway_response',
        'metadata',
        'ip_address',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'metadata'         => 'array',
        'amount'           => 'decimal:4',
        'paid_at'          => 'datetime',
        'refunded_at'      => 'datetime',
    ];

    /**
     * Status constants.
     */
    const STATUS_PENDING   = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED    = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED  = 'refunded';

    /**
     * Get the order.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass(), 'order_id');
    }

    /**
     * Get the invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(InvoiceProxy::modelClass(), 'invoice_id');
    }

    /**
     * Get the gateway.
     */
    public function gateway()
    {
        return $this->belongsTo(HondurasPayGateway::class, 'gateway_code', 'code');
    }

    /**
     * Scope for pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Check if transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if transaction can be refunded.
     */
    public function canRefund(): bool
    {
        return $this->status === self::STATUS_COMPLETED && is_null($this->refunded_at);
    }
}
