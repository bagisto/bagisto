<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplace_sellers', function (Blueprint $table) {
            if (! Schema::hasColumn('marketplace_sellers', 'stripe_account_id')) {
                $table->string('stripe_account_id')->nullable()->after('commission_rate');
                $table->boolean('stripe_charges_enabled')->default(false)->after('stripe_account_id');
                $table->boolean('stripe_payouts_enabled')->default(false)->after('stripe_charges_enabled');
                $table->string('stripe_customer_id')->nullable()->after('stripe_payouts_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('marketplace_sellers', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_account_id',
                'stripe_charges_enabled',
                'stripe_payouts_enabled',
                'stripe_customer_id',
            ]);
        });
    }
};
