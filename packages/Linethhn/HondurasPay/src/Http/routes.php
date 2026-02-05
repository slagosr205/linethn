<?php

use Illuminate\Support\Facades\Route;
use Linethhn\HondurasPay\Http\Controllers\Shop\PaymentController;
use Linethhn\HondurasPay\Http\Controllers\Admin\HondurasPayController;

/**
 * Shop Routes - Payment flow
 */
Route::group([
    'prefix'     => 'honduras-pay',
    'middleware' => ['web'],
], function () {
    Route::get('redirect', [PaymentController::class, 'redirect'])
        ->name('honduras-pay.redirect');

    Route::get('form', [PaymentController::class, 'showForm'])
        ->name('honduras-pay.form');

    Route::post('process-form', [PaymentController::class, 'processForm'])
        ->name('honduras-pay.process-form');

    Route::get('success', [PaymentController::class, 'success'])
        ->name('honduras-pay.success');

    Route::get('cancel', [PaymentController::class, 'cancel'])
        ->name('honduras-pay.cancel');
});

/**
 * Webhook route - CSRF exempt
 */
Route::post('honduras-pay/webhook', [PaymentController::class, 'webhook'])
    ->name('honduras-pay.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

/**
 * Admin Routes - Gateway management & transactions
 */
Route::group([
    'prefix'     => config('app.admin_url', 'admin') . '/honduras-pay',
    'middleware' => ['web', 'admin'],
], function () {
    Route::get('/', [HondurasPayController::class, 'index'])
        ->name('admin.honduras-pay.index');

    // Gateways CRUD
    Route::get('gateways', [HondurasPayController::class, 'gateways'])
        ->name('admin.honduras-pay.gateways.index');

    Route::get('gateways/create', [HondurasPayController::class, 'createGateway'])
        ->name('admin.honduras-pay.gateways.create');

    Route::post('gateways', [HondurasPayController::class, 'storeGateway'])
        ->name('admin.honduras-pay.gateways.store');

    Route::get('gateways/{id}/edit', [HondurasPayController::class, 'editGateway'])
        ->name('admin.honduras-pay.gateways.edit');

    Route::put('gateways/{id}', [HondurasPayController::class, 'updateGateway'])
        ->name('admin.honduras-pay.gateways.update');

    Route::delete('gateways/{id}', [HondurasPayController::class, 'deleteGateway'])
        ->name('admin.honduras-pay.gateways.delete');

    Route::post('gateways/{id}/test', [HondurasPayController::class, 'testConnection'])
        ->name('admin.honduras-pay.gateways.test');

    // Transactions
    Route::get('transactions', [HondurasPayController::class, 'transactions'])
        ->name('admin.honduras-pay.transactions.index');

    Route::get('transactions/{id}', [HondurasPayController::class, 'transactionDetail'])
        ->name('admin.honduras-pay.transactions.detail');

    Route::post('transactions/{id}/refund', [HondurasPayController::class, 'refund'])
        ->name('admin.honduras-pay.transactions.refund');
});
