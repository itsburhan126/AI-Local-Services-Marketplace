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
        Schema::create('quality_guidelines', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., Communication
            $table->longText('description'); // HTML or rich text
            $table->string('icon_class')->default('fas fa-star'); // FontAwesome class
            $table->string('color_class')->default('indigo'); // e.g., blue, purple, orange
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_guidelines');
    }
};
