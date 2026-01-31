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
        Schema::create('success_stories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role'); // e.g., Founder, Bloom Marketing
            $table->string('type'); // e.g., Business Owner, Freelancer, Startup
            $table->text('quote');
            $table->longText('story_content')->nullable(); // For detailed view if needed
            $table->string('image_path')->nullable(); // Hero image
            $table->string('avatar_path')->nullable(); // User avatar
            $table->string('service_category')->nullable(); // e.g., Branding, Web Dev
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('success_stories');
    }
};
