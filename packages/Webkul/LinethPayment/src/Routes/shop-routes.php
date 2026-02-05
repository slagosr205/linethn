<?php

use Illuminate\Support\Facades\Route;
use Webkul\LinethPayment\Http\Controllers\Shop\LinethPaymentController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'linethpayment'], function () {
    Route::get('', [LinethPaymentController::class, 'index'])->name('shop.linethpayment.index');
});