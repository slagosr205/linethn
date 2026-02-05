<?php

return [
    [
        'key'  => 'sales.payment_methods.honduras_pay',
        'name' => 'honduras-pay::app.admin.system.honduras-pay',
        'info' => 'honduras-pay::app.admin.system.honduras-pay-info',
        'sort' => 5,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'honduras-pay::app.admin.system.title',
                'type'          => 'text',
                'depends'       => 'active:1',
                'validation'    => 'required_if:active,1',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'honduras-pay::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'       => 'image',
                'title'      => 'honduras-pay::app.admin.system.logo',
                'type'       => 'image',
                'validation' => 'mimes:bmp,jpeg,jpg,png,webp',
            ],
            [
                'name'          => 'active',
                'title'         => 'honduras-pay::app.admin.system.status',
                'type'          => 'boolean',
                'channel_based' => true,
            ],
            [
                'name'    => 'sandbox',
                'title'   => 'honduras-pay::app.admin.system.sandbox',
                'type'    => 'boolean',
                'info'    => 'honduras-pay::app.admin.system.sandbox-info',
            ],
            [
                'name'          => 'gateway_provider',
                'title'         => 'honduras-pay::app.admin.system.gateway-provider',
                'type'          => 'select',
                'channel_based' => true,
                'options'       => [
                    [
                        'title' => 'BAC Credomatic',
                        'value' => 'bac_credomatic',
                    ],
                    [
                        'title' => 'Banco Atlántida (VISA/MC)',
                        'value' => 'banco_atlantida',
                    ],
                    [
                        'title' => 'Ficohsa',
                        'value' => 'ficohsa',
                    ],
                    [
                        'title' => 'Banpaís',
                        'value' => 'banpais',
                    ],
                    [
                        'title' => 'Banco de Occidente',
                        'value' => 'banco_occidente',
                    ],
                    [
                        'title' => 'Davivienda Honduras',
                        'value' => 'davivienda',
                    ],
                    [
                        'title' => 'Tigo Money',
                        'value' => 'tigo_money',
                    ],
                    [
                        'title' => 'Tengo (Banco Ficohsa)',
                        'value' => 'tengo',
                    ],
                    [
                        'title' => 'Personalizado / Otro',
                        'value' => 'custom',
                    ],
                ],
            ],
            [
                'name'       => 'api_key',
                'title'      => 'honduras-pay::app.admin.system.api-key',
                'type'       => 'password',
                'info'       => 'honduras-pay::app.admin.system.api-key-info',
                'depends'    => 'active:1',
                'validation' => 'required_if:active,1',
            ],
            [
                'name'       => 'api_secret',
                'title'      => 'honduras-pay::app.admin.system.api-secret',
                'type'       => 'password',
                'info'       => 'honduras-pay::app.admin.system.api-secret-info',
                'depends'    => 'active:1',
                'validation' => 'required_if:active,1',
            ],
            [
                'name'       => 'merchant_id',
                'title'      => 'honduras-pay::app.admin.system.merchant-id',
                'type'       => 'text',
                'info'       => 'honduras-pay::app.admin.system.merchant-id-info',
                'depends'    => 'active:1',
            ],
            [
                'name'       => 'terminal_id',
                'title'      => 'honduras-pay::app.admin.system.terminal-id',
                'type'       => 'text',
                'info'       => 'honduras-pay::app.admin.system.terminal-id-info',
            ],
            [
                'name'       => 'api_url',
                'title'      => 'honduras-pay::app.admin.system.api-url',
                'type'       => 'text',
                'info'       => 'honduras-pay::app.admin.system.api-url-info',
                'depends'    => 'gateway_provider:custom',
            ],
            [
                'name'    => 'currency',
                'title'   => 'honduras-pay::app.admin.system.currency',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Lempira (HNL)',
                        'value' => 'HNL',
                    ],
                    [
                        'title' => 'Dólar (USD)',
                        'value' => 'USD',
                    ],
                ],
            ],
            [
                'name'       => 'webhook_secret',
                'title'      => 'honduras-pay::app.admin.system.webhook-secret',
                'type'       => 'password',
                'info'       => 'honduras-pay::app.admin.system.webhook-secret-info',
            ],
            [
                'name'    => 'generate_invoice',
                'title'   => 'honduras-pay::app.admin.system.generate-invoice',
                'type'    => 'boolean',
            ],
            [
                'name'    => 'invoice_status',
                'title'   => 'honduras-pay::app.admin.system.invoice-status',
                'type'    => 'select',
                'depends' => 'generate_invoice:1',
                'options' => [
                    [
                        'title' => 'honduras-pay::app.admin.system.pending',
                        'value' => 'pending',
                    ],
                    [
                        'title' => 'honduras-pay::app.admin.system.paid',
                        'value' => 'paid',
                    ],
                ],
            ],
            [
                'name'    => 'order_status',
                'title'   => 'honduras-pay::app.admin.system.order-status',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'honduras-pay::app.admin.system.pending',
                        'value' => 'pending',
                    ],
                    [
                        'title' => 'honduras-pay::app.admin.system.pending-payment',
                        'value' => 'pending_payment',
                    ],
                    [
                        'title' => 'honduras-pay::app.admin.system.processing',
                        'value' => 'processing',
                    ],
                ],
            ],
            [
                'name'    => 'sort',
                'title'   => 'honduras-pay::app.admin.system.sort-order',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => '1',
                        'value' => 1,
                    ],
                    [
                        'title' => '2',
                        'value' => 2,
                    ],
                    [
                        'title' => '3',
                        'value' => 3,
                    ],
                    [
                        'title' => '4',
                        'value' => 4,
                    ],
                    [
                        'title' => '5',
                        'value' => 5,
                    ],
                ],
            ],
        ],
    ],
];
