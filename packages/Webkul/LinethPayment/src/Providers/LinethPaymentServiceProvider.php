<?php

namespace Webkul\LinethPayment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class LinethPaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'linethpayment');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'linethpayment');

        $this->publishes([
            __DIR__ . '/../Resources/lang' => resource_path('lang/vendor/linethpayment'),
        ], 'linethpayment-translations');

        Event::listen('bagisto.admin.layout.head', function ($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('linethpayment::admin.layouts.style');
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php',
            'acl'
        );
    }
}
