<?php

return [
    'admin' => [
        'system' => [
            'honduras-pay'       => 'Pago Bancario Honduras',
            'honduras-pay-info'  => 'Pasarela de pagos bancarios para Honduras (BAC, Atlántida, Ficohsa, Banpaís, etc.)',
            'title'              => 'Título',
            'description'        => 'Descripción',
            'logo'               => 'Logo',
            'status'             => 'Estado',
            'sandbox'            => 'Modo Sandbox',
            'sandbox-info'       => 'Active el modo sandbox para realizar pruebas sin procesar pagos reales',
            'gateway-provider'   => 'Proveedor/Banco',
            'api-key'            => 'API Key',
            'api-key-info'       => 'Clave de API proporcionada por el banco o pasarela de pagos',
            'api-secret'         => 'API Secret',
            'api-secret-info'    => 'Secret de API proporcionado por el banco (se almacena de forma segura)',
            'merchant-id'        => 'Merchant ID',
            'merchant-id-info'   => 'Identificador del comercio en la pasarela bancaria',
            'terminal-id'        => 'Terminal ID',
            'terminal-id-info'   => 'Identificador de terminal (si aplica)',
            'api-url'            => 'URL de API Personalizada',
            'api-url-info'       => 'URL base de la API para pasarelas personalizadas',
            'currency'           => 'Moneda',
            'webhook-secret'     => 'Webhook Secret',
            'webhook-secret-info' => 'Secret para verificar las notificaciones del banco',
            'generate-invoice'   => 'Generar Factura Automática',
            'invoice-status'     => 'Estado de Factura',
            'order-status'       => 'Estado de Orden',
            'sort-order'         => 'Orden de Visualización',
            'pending'            => 'Pendiente',
            'paid'               => 'Pagado',
            'pending-payment'    => 'Pago Pendiente',
            'processing'         => 'Procesando',
        ],
    ],

    'shop' => [
        'checkout' => [
            'honduras-pay-title'       => 'Pago con Tarjeta (Bancos de Honduras)',
            'honduras-pay-description' => 'Pague de forma segura con su tarjeta de débito o crédito a través de bancos hondureños',
            'processing'               => 'Procesando su pago...',
            'redirect-message'         => 'Será redirigido a la pasarela de pago de su banco',
        ],
    ],
];
