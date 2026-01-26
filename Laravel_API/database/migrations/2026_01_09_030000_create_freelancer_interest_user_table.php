<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freelancer_interest_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('freelancer_interest_id')->constrained('freelancer_interests')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'freelancer_interest_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelancer_interest_user');
    }
};
