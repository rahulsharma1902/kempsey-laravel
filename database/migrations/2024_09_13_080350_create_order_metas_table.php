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
        Schema::create('order_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Foreign key reference to products table
            $table->unsignedBigInteger('order_id');   // Foreign key reference to orders table
            $table->unsignedInteger('qty'); // Quantity as unsigned integer since it can't be negative
            $table->decimal('item_price', 10, 2); // Price of a single item
            $table->decimal('total_price', 10, 2); // Total price for all items of this product in the order
            $table->decimal('shipping_price', 10, 2)->nullable(); // Shipping price (nullable in case not applicable)
            $table->decimal('coupon_price', 10, 2)->nullable(); // Discount amount from coupon (nullable in case not applicable)
            $table->decimal('additional_price', 10, 2)->nullable(); // Additional charges (nullable in case not applicable)
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_metas');
    }
};
