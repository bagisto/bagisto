<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            $table->decimal('tax_percent', 12, 4)->default(0)->after('base_discount_amount');
            $table->decimal('tax_amount', 12, 4)->default(0)->after('tax_percent');
            $table->decimal('base_tax_amount', 12, 4)->default(0)->after('tax_amount');

            $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_tax_amount');
            $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');

            $table->string('applied_tax_rate')->nullable()->after('base_price_incl_tax');
        });

        DB::table('cart_items')->update([
            'price_incl_tax'      => DB::raw('price'),
            'base_price_incl_tax' => DB::raw('base_price'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_shipping_rates', function (Blueprint $table) {
            $table->dropColumn('tax_amount');
            $table->dropColumn('base_tax_amount');
            $table->dropColumn('price_incl_tax');
            $table->dropColumn('base_price_incl_tax');
            $table->dropColumn('applied_taxes');
        });
    }
};
