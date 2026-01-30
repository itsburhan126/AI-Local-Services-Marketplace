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
        // Update Gigs Table for Flash Sales
        Schema::table('gigs', function (Blueprint $table) {
            if (!Schema::hasColumn('gigs', 'is_flash_sale')) {
                $table->boolean('is_flash_sale')->default(false);
            }
            if (!Schema::hasColumn('gigs', 'discount_percentage')) {
                $table->integer('discount_percentage')->nullable();
            }
            if (!Schema::hasColumn('gigs', 'flash_sale_end_time')) {
                $table->timestamp('flash_sale_end_time')->nullable();
            }
        });

        // Update Banners Table for Types and Links
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'type')) {
                $table->string('type')->default('hero'); // hero, promo_large, promo_split
            }
            if (!Schema::hasColumn('banners', 'subtitle')) {
                $table->string('subtitle')->nullable();
            }
            // 'link' already exists
            if (!Schema::hasColumn('banners', 'button_text')) {
                $table->string('button_text')->nullable();
            }
            if (!Schema::hasColumn('banners', 'position')) {
                $table->string('position')->nullable(); // left, right (for split banners)
            }
        });

        // Create Recently Viewed Gigs Table
        if (!Schema::hasTable('recently_viewed_gigs')) {
            Schema::create('recently_viewed_gigs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('gig_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['user_id', 'gig_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recently_viewed_gigs');

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['type', 'subtitle', 'button_text', 'position']);
        });

        Schema::table('gigs', function (Blueprint $table) {
            $table->dropColumn(['is_flash_sale', 'discount_percentage', 'flash_sale_end_time']);
        });
    }
};
