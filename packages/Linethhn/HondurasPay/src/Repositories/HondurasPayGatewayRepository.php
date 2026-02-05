<?php

namespace Linethhn\HondurasPay\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Crypt;

class HondurasPayGatewayRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Linethhn\HondurasPay\Contracts\HondurasPayGateway';
    }

    /**
     * Create a new gateway with encrypted secrets.
     */
    public function create(array $data): mixed
    {
        if (! empty($data['api_secret'])) {
            $data['api_secret'] = Crypt::encryptString($data['api_secret']);
        }

        if (! empty($data['webhook_secret'])) {
            $data['webhook_secret'] = Crypt::encryptString($data['webhook_secret']);
        }

        return parent::create($data);
    }

    /**
     * Update a gateway with encrypted secrets.
     */
    public function update(array $data, $id, $attribute = 'id'): mixed
    {
        if (! empty($data['api_secret'])) {
            $data['api_secret'] = Crypt::encryptString($data['api_secret']);
        } else {
            unset($data['api_secret']);
        }

        if (! empty($data['webhook_secret'])) {
            $data['webhook_secret'] = Crypt::encryptString($data['webhook_secret']);
        } else {
            unset($data['webhook_secret']);
        }

        return parent::update($data, $id, $attribute);
    }

    /**
     * Get active gateways.
     */
    public function getActiveGateways()
    {
        return $this->model->where('active', true)->orderBy('sort_order')->get();
    }

    /**
     * Get gateway by code.
     */
    public function findByCode(string $code)
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * Get decrypted API secret for a gateway.
     */
    public function getDecryptedSecret($gateway): ?string
    {
        if (empty($gateway->api_secret)) {
            return null;
        }

        try {
            return Crypt::decryptString($gateway->api_secret);
        } catch (\Exception $e) {
            return $gateway->api_secret;
        }
    }
}
