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
        Schema::create('faq_contents', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image_url')->nullable();
            $table->string('sub_heading')->nullable();
            $table->string('heading')->nullable();
            $table->string('content_heading')->nullable();
            $table->text('additional_data')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_contents');
    }
};
