<?php

return [
    'admin' => [
        'system' => [
            'honduras-pay'       => 'Honduras Bank Payment',
            'honduras-pay-info'  => 'Bank payment gateway for Honduras (BAC, Atlantida, Ficohsa, Banpais, etc.)',
            'title'              => 'Title',
            'description'        => 'Description',
            'logo'               => 'Logo',
            'status'             => 'Status',
            'sandbox'            => 'Sandbox Mode',
            'sandbox-info'       => 'Enable sandbox mode to test without processing real payments',
            'gateway-provider'   => 'Provider/Bank',
            'api-key'            => 'API Key',
            'api-key-info'       => 'API key provided by the bank or payment gateway',
            'api-secret'         => 'API Secret',
            'api-secret-info'    => 'API secret provided by the bank (stored securely)',
            'merchant-id'        => 'Merchant ID',
            'merchant-id-info'   => 'Merchant identifier in the banking gateway',
            'terminal-id'        => 'Terminal ID',
            'terminal-id-info'   => 'Terminal identifier (if applicable)',
            'api-url'            => 'Custom API URL',
            'api-url-info'       => 'Base API URL for custom gateways',
            'currency'           => 'Currency',
            'webhook-secret'     => 'Webhook Secret',
            'webhook-secret-info' => 'Secret to verify bank notifications',
            'generate-invoice'   => 'Auto-generate Invoice',
            'invoice-status'     => 'Invoice Status',
            'order-status'       => 'Order Status',
            'sort-order'         => 'Sort Order',
            'pending'            => 'Pending',
            'paid'               => 'Paid',
            'pending-payment'    => 'Pending Payment',
            'processing'         => 'Processing',
        ],
    ],

    'shop' => [
        'checkout' => [
            'honduras-pay-title'       => 'Card Payment (Honduras Banks)',
            'honduras-pay-description' => 'Pay securely with your debit or credit card through Honduran banks',
            'processing'               => 'Processing your payment...',
            'redirect-message'         => 'You will be redirected to your bank\'s payment gateway',
        ],
    ],
];
