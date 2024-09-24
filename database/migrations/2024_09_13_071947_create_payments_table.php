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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('payment_method'); //  'paypal', 'stripe'
            $table->decimal('amount', 10, 2);
            $table->string('currency'); 
            $table->string('transaction_id')->unique()->nullable();
            $table->string('payment_status')->default('pending'); // e.g., 'pending', 'completed', 'failed'
            $table->text('payment_details')->nullable(); // JSON or text for storing extra details
            $table->integer('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
