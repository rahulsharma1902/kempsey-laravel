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
        Schema::create('about_us_contents', function (Blueprint $table) {
            $table->id();
            $table->string('about_us_banner_title')->nullable();
            $table->string('about_us_banner_image')->nullable();
            $table->string('about_us_banner_sub_title')->nullable();

            $table->string('about_us_heading')->nullable();
            $table->string('about_us_logo')->nullable();
            $table->longtext('about_us_details')->nullable();
            $table->string('about_us_image')->nullable();
            $table->string('about_us_btn')->nullable();
            $table->string('about_us_btn_link')->nullable();

            $table->string('about_us_shop_title')->nullable();
            $table->json('about_us_shop_details')->nullable();
            
            $table->string('about_us_bottom_title')->nullable();
            $table->string('about_us_bottom_banner')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_us_contents');
    }
};
