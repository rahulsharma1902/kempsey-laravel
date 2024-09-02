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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->unsignedBigInteger('category_id');
        $table->unsignedBigInteger('brand_id')->nullable();
        $table->json('selected_filters_options')->nullable();
        $table->longText('description')->nullable();
        $table->longText('details')->nullable();
        $table->decimal('price', 10, 2);
        $table->unsignedInteger('stock')->default(0);
        $table->decimal('weight', 8, 2)->nullable();
        $table->json('images')->nullable();
        $table->unsignedInteger('thumbnail_index')->nullable();
        $table->timestamps();

        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');

        $table->index('category_id');
        $table->index('brand_id');
        $table->index('price');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('products');
    }


};
