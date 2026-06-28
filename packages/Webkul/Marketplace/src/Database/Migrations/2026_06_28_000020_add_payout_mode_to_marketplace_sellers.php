<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplace_sellers', function (Blueprint $table) {
            if (! Schema::hasColumn('marketplace_sellers', 'payout_mode')) {
                // 'platform' = all money stays with the marketplace owner (manual payout)
                // 'stripe'   = settled to the seller via Stripe Connect
                $table->string('payout_mode')->default('platform')->after('stripe_customer_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('marketplace_sellers', function (Blueprint $table) {
            $table->dropColumn('payout_mode');
        });
    }
};
