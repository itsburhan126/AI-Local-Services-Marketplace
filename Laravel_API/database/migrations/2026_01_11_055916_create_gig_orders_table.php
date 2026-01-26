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
        Schema::create('gig_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Customer
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade'); // Provider (User table)
            $table->foreignId('gig_id')->constrained('gigs')->onDelete('cascade');
            $table->foreignId('gig_package_id')->constrained('gig_packages')->onDelete('cascade');
            
            $table->string('status')->default('pending'); // pending, accepted, in_progress, completed, cancelled, disputed
            $table->dateTime('scheduled_at')->nullable(); // Or delivery_date
            
            $table->decimal('total_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('provider_amount', 10, 2)->default(0);
            
            $table->string('payment_status')->default('pending'); // pending, paid, refunded, failed
            $table->string('payment_method')->nullable(); // stripe, paypal, cash, wallet
            
            $table->text('notes')->nullable();
            $table->text('address')->nullable(); // For online gigs maybe just 'Online' or null
            $table->json('extras')->nullable(); // Store selected extras
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_orders');
    }
};
