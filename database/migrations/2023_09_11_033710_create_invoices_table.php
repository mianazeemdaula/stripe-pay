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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_gateway_id');
            $table->unsignedBigInteger('product_id');
            $table->string('invoice_id', 10)->unique();
            $table->string('payment_id', 120)->nullable()->unique();
            $table->json('response')->nullable();
            $table->json('data')->nullable();
            $table->string('status',10)->default('pending');
            $table->float('amount')->default(0);
            $table->float('amount_paid')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
