<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honduras_pay_gateways', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->string('merchant_id')->nullable();
            $table->string('terminal_id')->nullable();
            $table->string('api_url_production')->nullable();
            $table->string('api_url_sandbox')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->string('currency')->default('HNL');
            $table->boolean('sandbox')->default(true);
            $table->boolean('active')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('additional_config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honduras_pay_gateways');
    }
};
