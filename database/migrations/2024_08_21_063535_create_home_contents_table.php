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
        Schema::create('home_contents', function (Blueprint $table) {
            $table->id();
            $table->string('closet_section_heading')->nullable();
            $table->string('closet_section_sub_heading')->nullable();
            $table->string('closet_section_btn')->nullable();
            $table->string('closet_section_btn_link')->nullable();
            $table->string('closet_section_banner')->nullable();
            $table->string('closet_section_banner_heading')->nullable();

            $table->string('new_arrivals_first_banner')->nullable();
            $table->string('new_arrivals_bg_image')->nullable();
            $table->string('new_arrivals_title')->nullable();
            $table->text('new_arrivals_text')->nullable();
            $table->string('new_arrivals_btn')->nullable();
            $table->string('new_arrivals_btn_link')->nullable();
            $table->string('new_arrivals_logo')->nullable();
            $table->string('new_arrivals_product_image')->nullable();
            $table->string('new_arrivals_product_name')->nullable();
            $table->text('new_arrivals_product_text')->nullable();
            $table->string('new_arrivals_product_btn')->nullable();
            $table->string('new_arrivals_product_btn_link')->nullable();
            $table->string('new_arrivals_product_banner')->nullable();

            $table->string('about_section_heading')->nullable();
            $table->string('about_section_logo')->nullable();
            $table->longtext('about_section_details')->nullable();
            $table->string('about_section_image')->nullable();
            $table->string('about_section_btn')->nullable();
            $table->string('about_section_btn_link')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_contents');
    }
};
