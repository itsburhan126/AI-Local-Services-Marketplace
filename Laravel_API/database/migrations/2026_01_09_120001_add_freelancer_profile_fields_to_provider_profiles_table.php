<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('provider_profiles', 'country')) {
                $table->string('country')->nullable()->after('address');
            }
            if (!Schema::hasColumn('provider_profiles', 'languages')) {
                $table->json('languages')->nullable()->after('country');
            }
            if (!Schema::hasColumn('provider_profiles', 'skills')) {
                $table->json('skills')->nullable()->after('languages');
            }
        });
    }

    public function down(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('provider_profiles', 'skills')) {
                $table->dropColumn('skills');
            }
            if (Schema::hasColumn('provider_profiles', 'languages')) {
                $table->dropColumn('languages');
            }
            if (Schema::hasColumn('provider_profiles', 'country')) {
                $table->dropColumn('country');
            }
        });
    }
};

