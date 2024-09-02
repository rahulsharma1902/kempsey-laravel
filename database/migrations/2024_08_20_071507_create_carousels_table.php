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
        Schema::create('carousels', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->nullable();
            $table->string('sub_heading')->nullable();
            $table->text('text')->nullable();
            $table->string('button_text')->nullable();
            $table->text('button_link')->nullable();
            $table->text('image')->nullable();
            $table->integer('position')->default(0); 
            $table->timestamps();

            // Optionally, add indexes if needed
            // $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousels');
    }
};
