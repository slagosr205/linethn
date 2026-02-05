<?php

namespace Linethhn\HondurasPay\Providers;

use Illuminate\Support\ServiceProvider;

class HondurasPayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'honduras-pay');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'honduras-pay');

        $this->publishes([
            __DIR__ . '/../Resources/lang' => lang_path('vendor/honduras-pay'),
        ], 'honduras-pay-lang');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/honduras-pay'),
        ], 'honduras-pay-views');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/paymentmethods.php',
            'payment_methods'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/admin-menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/acl.php',
            'acl'
        );
    }
}
