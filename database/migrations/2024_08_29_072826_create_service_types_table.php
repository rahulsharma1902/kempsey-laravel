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
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('name'); 
            $table->string('slug'); 
            $table->decimal('full_price', 8, 2);
            $table->decimal('club_price', 8, 2);
            $table->longtext('details')->nullable();
            $table->timestamps();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_types');
    }
};
