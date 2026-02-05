<?php

namespace Webkul\LinethPayment\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\LinethPayment\Models\LinethPaymentModel::class,
    ];
}