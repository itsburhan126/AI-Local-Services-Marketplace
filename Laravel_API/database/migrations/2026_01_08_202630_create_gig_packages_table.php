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
        Schema::create('gig_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gig_id')->constrained()->cascadeOnDelete();
            $table->enum('tier', ['basic', 'standard', 'premium']);
            $table->string('name'); // e.g., "Silver Package"
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('delivery_days');
            $table->integer('revisions')->default(0); // 0 = unlimited or none, logic handled in app
            $table->boolean('source_code')->default(false);
            $table->json('features')->nullable(); // List of included features
            $table->timestamps();
            
            $table->unique(['gig_id', 'tier']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_packages');
    }
};
