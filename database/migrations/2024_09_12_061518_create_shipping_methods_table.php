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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50); // Shipping type (e.g., Standard, Express)
            $table->text('details'); // Description of the shipping method
            $table->decimal('price', 10, 2); // Price of the shipping method
            $table->boolean('is_free_shipping_enabled')->default(false); // Whether free shipping is enabled
            $table->decimal('free_shipping_over', 10, 2)->nullable(); // Minimum amount for free shipping
            $table->boolean('is_active')->default(true); // Whether the shipping method is active
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
