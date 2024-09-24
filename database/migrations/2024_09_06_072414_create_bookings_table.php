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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('bike_detail')->nullable();
            $table->string('bike_brand')->nullable();
            $table->string('bike_color')->nullable();
            $table->string('bike_model')->nullable();
            $table->string('bike_type')->nullable();
            $table->date('service_date')->nullable();
            $table->json('services')->nullable(); 
            $table->json('service_ids')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->json('types')->nullable();
            $table->string('user_fname')->nullable();
            $table->string('user_lname')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('hear_about_us')->nullable();
            $table->string('booking_number')->nullable();
            $table->string('service_price')->nullable();
            $table->integer('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
