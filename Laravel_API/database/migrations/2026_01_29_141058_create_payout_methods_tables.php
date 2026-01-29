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
        Schema::create('payout_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->json('fields')->nullable(); // JSON schema for required fields e.g. [{"name": "email", "label": "PayPal Email", "type": "email"}]
            $table->boolean('is_active')->default(true);
            $table->decimal('min_amount', 10, 2)->default(0);
            $table->decimal('max_amount', 10, 2)->nullable();
            $table->integer('processing_time_days')->default(1);
            $table->timestamps();
        });

        Schema::create('user_payout_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payout_method_id')->constrained()->cascadeOnDelete();
            $table->json('field_values'); // JSON data e.g. {"email": "user@example.com"}
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_payout_methods');
        Schema::dropIfExists('payout_methods');
    }
};
