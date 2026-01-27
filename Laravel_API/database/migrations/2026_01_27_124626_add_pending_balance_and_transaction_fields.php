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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('pending_balance', 10, 2)->default(0.00)->after('wallet_balance');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed')->after('type');
            $table->timestamp('available_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pending_balance');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('available_at');
        });
    }
};
