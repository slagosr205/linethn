<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('lineth_payments_module', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('name')->unique();
            $table->string('button_code')->uniqidue();
            $table->string('sort_order');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('lineth_payments_module');
    }
};
