<?php

use Illuminate\Support\Facades\Route;
use Webkul\LinethPayment\Http\Controllers\Admin\LinethPaymentController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/linethpayment'], function () {
    Route::controller(LinethPaymentController::class)->group(function () {
        Route::get('/', 'index')->name('admin.linethpayment.index');
    });

    Route::get('/datagrid', [LinethPaymentController::class, 'datagrid'])
        ->name('admin.linethpayment.datagrid');

    Route::put('/edit/{id}', [LinethPaymentController::class, 'edit'])
        ->name('admin.linethpayment.edit');

    Route::get('/create', [LinethPaymentController::class, 'create'])
        ->name('admin.linethpayment.create');

    Route::get('/store', [LinethPaymentController::class, 'store'])
        ->name('admin.linethpayment.store');
});
