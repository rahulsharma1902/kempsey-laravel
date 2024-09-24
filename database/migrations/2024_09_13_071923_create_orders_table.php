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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->string('order_number')->unique(); 
            $table->string('confirmation_email')->nullable();
            $table->unsignedBigInteger('billing_address_id')->nullable(); 
            $table->unsignedBigInteger('shipping_address_id');
            $table->unsignedBigInteger('shipping_method')->nullable(); 
            $table->decimal('additional_charge', 10, 2)->default(0.00); 
            $table->unsignedBigInteger('coupon_id')->nullable(); 
            $table->string('payment_method'); 
            $table->string('currency', 3); 
            $table->integer('billing_address_same_as_shipping')->default(1)->nullable(); 
            
            $table->decimal('total_price', 10, 2);
            $table->decimal('price', 10, 2);
            $table->enum('order_status', ['pending', 'processing', 'completed', 'cancelled','succeeded'])->default('pending'); 
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('shipping_address_id')->references('id')->on('shipping_addresses')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
