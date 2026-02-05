<?php

namespace Linethhn\HondurasPay\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     */
    protected $listen = [
        'sales.invoice.save.after' => [
            'Linethhn\HondurasPay\Listeners\TransactionListener@saveTransaction',
        ],
        'checkout.order.save.after' => [
            'Linethhn\HondurasPay\Listeners\TransactionListener@orderCreated',
        ],
    ];
}
