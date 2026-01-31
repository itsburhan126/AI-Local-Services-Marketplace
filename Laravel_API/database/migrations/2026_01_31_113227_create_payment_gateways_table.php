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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // paypal, stripe, etc.
            $table->string('title'); // Display name e.g. "PayPal"
            $table->json('credentials')->nullable(); // client_id, secret, etc.
            $table->boolean('is_active')->default(true);
            $table->enum('mode', ['sandbox', 'live'])->default('sandbox');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
