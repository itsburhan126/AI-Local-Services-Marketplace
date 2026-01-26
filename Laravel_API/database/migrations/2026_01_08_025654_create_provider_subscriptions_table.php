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
        Schema::create('provider_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Provider user id
            $table->foreignId('subscription_plan_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->string('payment_id')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_subscriptions');
    }
};
