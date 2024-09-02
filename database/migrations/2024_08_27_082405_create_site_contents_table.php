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
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('header_offer_text')->nullable();
            $table->string('footer_instagram_name')->nullable();
            $table->json('footer_instagram_images')->nullable();
            $table->string('footer_contact_title')->nullable();
            $table->string('footer_contact_banner')->nullable();
            $table->string('footer_facebook_link')->nullable();
            $table->string('footer_instagram_link')->nullable();
            $table->string('footer_twitter_link')->nullable();
            $table->string('footer_slider_image')->nullable();
            $table->text('footer_description')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('footer_policy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_contents');
    }
};
