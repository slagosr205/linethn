# Honduras Pay - Pasarela de Pagos Bancarios para Honduras

Módulo de pasarela de pagos bancarios para **Bagisto E-commerce**, diseñado específicamente para Honduras donde los bancos actúan como intermediarios de pagos.

## Bancos/Pasarelas Soportadas

- **BAC Credomatic**
- **Banco Atlántida** (VISA/MasterCard)
- **Ficohsa**
- **Banpaís**
- **Banco de Occidente**
- **Davivienda Honduras**
- **Tigo Money**
- **Tengo** (Banco Ficohsa)
- **Personalizado/Otro** (cualquier pasarela con API REST)

## Características

- Configuración de API Key, API Secret, Merchant ID y Terminal ID desde el Admin
- Soporte para modo Sandbox (pruebas) y Producción
- Soporte para Lempiras (HNL) y Dólares (USD)
- Panel de control con estadísticas de ventas
- Historial de transacciones con filtros
- Gestión de múltiples pasarelas bancarias
- Webhooks para notificaciones automáticas del banco
- Generación automática de facturas
- Procesamiento de reembolsos
- Integración completa con el módulo de ventas de Bagisto

## Instalación

### 1. Copiar el paquete

Copie la carpeta `packages/Linethhn/HondurasPay` a su instalación de Bagisto.

### 2. Registrar el autoload

En `composer.json` del proyecto raíz, agregue en la sección `autoload.psr-4`:

```json
"Linethhn\\HondurasPay\\": "packages/Linethhn/HondurasPay/src"
```

### 3. Registrar el módulo en Concord

En `config/concord.php`, agregue al array `modules`:

```php
\Linethhn\HondurasPay\Providers\ModuleServiceProvider::class,
```

### 4. Ejecutar los comandos

```bash
composer dump-autoload
php artisan migrate
php artisan config:cache
```

### 5. Configurar en el Admin

1. Vaya a **Configuración > Ventas > Métodos de Pago**
2. Busque **"Pago Bancario Honduras"**
3. Active el método y configure:
   - Seleccione su banco/pasarela
   - Ingrese su API Key y API Secret
   - Configure el Merchant ID y Terminal ID
   - Seleccione la moneda (HNL o USD)
   - Active/desactive modo Sandbox

### 6. Gestión de pasarelas

Acceda al menú **Honduras Pay** en el panel de administración para:
- Agregar y configurar pasarelas bancarias
- Ver el historial de transacciones
- Procesar reembolsos
- Ver estadísticas de ventas

## Configuración del Webhook

Configure la siguiente URL en el panel de su banco/pasarela para recibir notificaciones automáticas:

```
https://su-dominio.com/honduras-pay/webhook
```

## Estructura del Paquete

```
packages/Linethhn/HondurasPay/
├── composer.json
├── README.md
└── src/
    ├── Config/
    │   ├── acl.php
    │   ├── admin-menu.php
    │   ├── paymentmethods.php
    │   └── system.php
    ├── Contracts/
    │   ├── HondurasPayGateway.php
    │   └── HondurasPayTransaction.php
    ├── Database/Migrations/
    │   ├── 2024_01_01_000001_create_honduras_pay_gateways_table.php
    │   └── 2024_01_01_000002_create_honduras_pay_transactions_table.php
    ├── Http/
    │   ├── Controllers/
    │   │   ├── Admin/HondurasPayController.php
    │   │   └── Shop/PaymentController.php
    │   └── routes.php
    ├── Listeners/
    │   └── TransactionListener.php
    ├── Models/
    │   ├── HondurasPayGateway.php
    │   ├── HondurasPayGatewayProxy.php
    │   ├── HondurasPayTransaction.php
    │   └── HondurasPayTransactionProxy.php
    ├── Payment/
    │   └── HondurasPay.php
    ├── Providers/
    │   ├── EventServiceProvider.php
    │   ├── HondurasPayServiceProvider.php
    │   └── ModuleServiceProvider.php
    ├── Repositories/
    │   ├── HondurasPayGatewayRepository.php
    │   └── HondurasPayTransactionRepository.php
    ├── Resources/
    │   ├── lang/
    │   │   ├── en/app.php
    │   │   └── es/app.php
    │   └── views/
    │       ├── admin/
    │       │   ├── gateways/
    │       │   │   ├── create.blade.php
    │       │   │   ├── edit.blade.php
    │       │   │   └── index.blade.php
    │       │   ├── transactions/
    │       │   │   ├── detail.blade.php
    │       │   │   └── index.blade.php
    │       │   └── index.blade.php
    │       └── shop/
    │           └── checkout/
    │               ├── payment-form.blade.php
    │               └── redirect-form.blade.php
    └── Services/
        └── PaymentGatewayService.php
```

## Licencia

MIT
