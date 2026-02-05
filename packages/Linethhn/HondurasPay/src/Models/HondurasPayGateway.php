<?php

namespace Linethhn\HondurasPay\Models;

use Illuminate\Database\Eloquent\Model;
use Linethhn\HondurasPay\Contracts\HondurasPayGateway as HondurasPayGatewayContract;

class HondurasPayGateway extends Model implements HondurasPayGatewayContract
{
    protected $table = 'honduras_pay_gateways';

    protected $fillable = [
        'name',
        'code',
        'api_key',
        'api_secret',
        'merchant_id',
        'terminal_id',
        'api_url_production',
        'api_url_sandbox',
        'webhook_secret',
        'currency',
        'sandbox',
        'active',
        'sort_order',
        'additional_config',
    ];

    protected $casts = [
        'sandbox'           => 'boolean',
        'active'            => 'boolean',
        'additional_config' => 'array',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
        'webhook_secret',
    ];

    /**
     * Get the transactions for this gateway.
     */
    public function transactions()
    {
        return $this->hasMany(HondurasPayTransaction::class, 'gateway_code', 'code');
    }

    /**
     * Get the active API URL based on sandbox mode.
     */
    public function getActiveApiUrl(): string
    {
        return $this->sandbox
            ? ($this->api_url_sandbox ?? '')
            : ($this->api_url_production ?? '');
    }
}
