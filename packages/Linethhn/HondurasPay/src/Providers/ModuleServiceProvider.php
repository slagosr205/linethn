<?php

namespace Linethhn\HondurasPay\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Linethhn\HondurasPay\Models\HondurasPayTransaction::class,
        \Linethhn\HondurasPay\Models\HondurasPayGateway::class,
    ];
}
