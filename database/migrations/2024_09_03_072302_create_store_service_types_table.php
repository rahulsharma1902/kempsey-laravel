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
        Schema::create('store_service_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_service_id');
            // $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('service_type_id')->nullable();

            $table->foreign('store_service_id')->references('id')->on('store_services')->onDelete('cascade');
            // $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_service_types');
    }
};
