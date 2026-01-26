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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable()->change();
            $table->foreignId('gig_id')->nullable()->constrained('gigs')->onDelete('cascade');
            $table->foreignId('gig_package_id')->nullable()->constrained('gig_packages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable(false)->change();
            $table->dropForeign(['gig_id']);
            $table->dropColumn('gig_id');
            $table->dropForeign(['gig_package_id']);
            $table->dropColumn('gig_package_id');
        });
    }
};
