<?php

namespace Linethhn\HondurasPay\Payment;

use Webkul\Payment\Payment\Payment;

class HondurasPay extends Payment
{
    /**
     * Payment method code.
     */
    protected $code = 'honduras_pay';

    /**
     * Get the redirect URL for the payment gateway.
     */
    public function getRedirectUrl(): string
    {
        return route('honduras-pay.redirect');
    }

    /**
     * Get the gateway provider configured.
     */
    public function getGatewayProvider(): string
    {
        return $this->getConfigData('gateway_provider') ?? 'custom';
    }

    /**
     * Check if sandbox mode is enabled.
     */
    public function isSandbox(): bool
    {
        return (bool) $this->getConfigData('sandbox');
    }

    /**
     * Get the API key.
     */
    public function getApiKey(): ?string
    {
        return $this->getConfigData('api_key');
    }

    /**
     * Get the API secret.
     */
    public function getApiSecret(): ?string
    {
        return $this->getConfigData('api_secret');
    }

    /**
     * Get the merchant ID.
     */
    public function getMerchantId(): ?string
    {
        return $this->getConfigData('merchant_id');
    }

    /**
     * Get the terminal ID.
     */
    public function getTerminalId(): ?string
    {
        return $this->getConfigData('terminal_id');
    }

    /**
     * Get the webhook secret.
     */
    public function getWebhookSecret(): ?string
    {
        return $this->getConfigData('webhook_secret');
    }

    /**
     * Get the configured currency.
     */
    public function getPaymentCurrency(): string
    {
        return $this->getConfigData('currency') ?? 'HNL';
    }

    /**
     * Get the API base URL for the configured gateway.
     */
    public function getApiBaseUrl(): string
    {
        $provider = $this->getGatewayProvider();
        $sandbox = $this->isSandbox();

        $urls = $this->getGatewayUrls();

        if ($provider === 'custom') {
            return $this->getConfigData('api_url') ?? '';
        }

        if (isset($urls[$provider])) {
            return $sandbox
                ? ($urls[$provider]['sandbox'] ?? $urls[$provider]['production'])
                : $urls[$provider]['production'];
        }

        return '';
    }

    /**
     * Get the known gateway URLs.
     */
    protected function getGatewayUrls(): array
    {
        return [
            'bac_credomatic' => [
                'production' => 'https://credomatic.com/cgi-bin/charge.asp',
                'sandbox'    => 'https://sandbox.credomatic.com/cgi-bin/charge.asp',
            ],
            'banco_atlantida' => [
                'production' => 'https://vpayment.atlantida.hn/api/v1',
                'sandbox'    => 'https://sandbox-vpayment.atlantida.hn/api/v1',
            ],
            'ficohsa' => [
                'production' => 'https://epago.ficohsa.com/api/v1',
                'sandbox'    => 'https://sandbox-epago.ficohsa.com/api/v1',
            ],
            'banpais' => [
                'production' => 'https://pago.banpais.hn/api/v1',
                'sandbox'    => 'https://sandbox-pago.banpais.hn/api/v1',
            ],
            'banco_occidente' => [
                'production' => 'https://pagos.bancooccidente.hn/api/v1',
                'sandbox'    => 'https://sandbox-pagos.bancooccidente.hn/api/v1',
            ],
            'davivienda' => [
                'production' => 'https://epagos.davivienda.hn/api/v1',
                'sandbox'    => 'https://sandbox-epagos.davivienda.hn/api/v1',
            ],
            'tigo_money' => [
                'production' => 'https://api.tigo.com.hn/payments/v1',
                'sandbox'    => 'https://sandbox.api.tigo.com.hn/payments/v1',
            ],
            'tengo' => [
                'production' => 'https://api.tengo.hn/v1/payments',
                'sandbox'    => 'https://sandbox.api.tengo.hn/v1/payments',
            ],
        ];
    }

    /**
     * Build the payment request payload.
     */
    public function buildPaymentPayload(array $orderData): array
    {
        $provider = $this->getGatewayProvider();

        $basePayload = [
            'merchant_id'  => $this->getMerchantId(),
            'terminal_id'  => $this->getTerminalId(),
            'amount'       => $orderData['grand_total'],
            'currency'     => $this->getPaymentCurrency(),
            'order_id'     => $orderData['order_id'],
            'description'  => 'Orden #' . $orderData['order_id'],
            'return_url'   => route('honduras-pay.success'),
            'cancel_url'   => route('honduras-pay.cancel'),
            'callback_url' => route('honduras-pay.webhook'),
            'customer'     => [
                'name'  => $orderData['customer_name'] ?? '',
                'email' => $orderData['customer_email'] ?? '',
                'phone' => $orderData['customer_phone'] ?? '',
            ],
        ];

        return $this->adaptPayloadForProvider($provider, $basePayload);
    }

    /**
     * Adapt payload format for specific gateway providers.
     */
    protected function adaptPayloadForProvider(string $provider, array $payload): array
    {
        switch ($provider) {
            case 'bac_credomatic':
                return [
                    'type'        => 'auth',
                    'key_id'      => $this->getApiKey(),
                    'hash'        => $this->generateBacHash($payload),
                    'time'        => time(),
                    'orderid'     => $payload['order_id'],
                    'amount'      => number_format($payload['amount'], 2, '.', ''),
                    'ccnumber'    => '',
                    'ccexp'       => '',
                    'redirect'    => $payload['return_url'],
                ];

            case 'tigo_money':
            case 'tengo':
                return [
                    'msisdn'          => $payload['customer']['phone'] ?? '',
                    'amount'          => $payload['amount'],
                    'currency'        => $payload['currency'],
                    'reference'       => $payload['order_id'],
                    'callback_url'    => $payload['callback_url'],
                    'redirect_url'    => $payload['return_url'],
                    'description'     => $payload['description'],
                ];

            default:
                return $payload;
        }
    }

    /**
     * Generate BAC Credomatic hash.
     */
    protected function generateBacHash(array $payload): string
    {
        $hashString = $payload['order_id']
            . '|' . number_format($payload['amount'], 2, '.', '')
            . '|' . time()
            . '|' . $this->getApiSecret();

        return md5($hashString);
    }

    /**
     * Get additional details for checkout display.
     */
    public function getAdditionalDetails(): array
    {
        $provider = $this->getGatewayProvider();

        $providerNames = [
            'bac_credomatic'  => 'BAC Credomatic',
            'banco_atlantida' => 'Banco Atlántida',
            'ficohsa'         => 'Ficohsa',
            'banpais'         => 'Banpaís',
            'banco_occidente' => 'Banco de Occidente',
            'davivienda'      => 'Davivienda Honduras',
            'tigo_money'      => 'Tigo Money',
            'tengo'           => 'Tengo',
            'custom'          => 'Pasarela Personalizada',
        ];

        return [
            'provider'      => $providerNames[$provider] ?? $provider,
            'currency'      => $this->getPaymentCurrency(),
            'sandbox'       => $this->isSandbox(),
        ];
    }
}
