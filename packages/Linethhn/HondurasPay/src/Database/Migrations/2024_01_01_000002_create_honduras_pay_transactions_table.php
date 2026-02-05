<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honduras_pay_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id')->nullable()->index();
            $table->string('gateway_code');
            $table->integer('order_id')->unsigned()->nullable();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->string('status')->default('pending');
            $table->string('type')->nullable();
            $table->decimal('amount', 12, 4)->default(0);
            $table->string('currency', 5)->default('HNL');
            $table->string('authorization_code')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_brand')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('error_message')->nullable();
            $table->json('gateway_response')->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('set null');

            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honduras_pay_transactions');
    }
};
