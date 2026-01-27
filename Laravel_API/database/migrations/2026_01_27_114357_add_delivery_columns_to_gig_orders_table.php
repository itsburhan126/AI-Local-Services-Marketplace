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
        Schema::table('gig_orders', function (Blueprint $table) {
            $table->text('delivery_note')->nullable()->after('extras');
            $table->json('delivery_files')->nullable()->after('delivery_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gig_orders', function (Blueprint $table) {
            $table->dropColumn('delivery_note');
            $table->dropColumn('delivery_files');
        });
    }
};
