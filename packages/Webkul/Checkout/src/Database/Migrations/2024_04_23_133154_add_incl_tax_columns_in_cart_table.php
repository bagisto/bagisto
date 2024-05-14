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
        Schema::table('cart', function (Blueprint $table) {
            $table->decimal('shipping_amount', 12, 4)->default(0)->after('base_discount_amount');
            $table->decimal('base_shipping_amount', 12, 4)->default(0)->after('shipping_amount');

            $table->decimal('shipping_amount_incl_tax', 12, 4)->default(0)->after('base_shipping_amount');
            $table->decimal('base_shipping_amount_incl_tax', 12, 4)->default(0)->after('shipping_amount_incl_tax');

            $table->decimal('sub_total_incl_tax', 12, 4)->default(0)->after('base_shipping_amount_incl_tax');
            $table->decimal('base_sub_total_incl_tax', 12, 4)->default(0)->after('sub_total_incl_tax');
        });

        DB::table('cart')->update([
            'sub_total_incl_tax'      => DB::raw('sub_total + tax_total'),
            'base_sub_total_incl_tax' => DB::raw('base_sub_total + base_tax_total'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('shipping_amount');
            $table->dropColumn('base_shipping_amount');
            $table->dropColumn('shipping_amount_incl_tax');
            $table->dropColumn('base_shipping_amount_incl_tax');
            $table->dropColumn('sub_total_incl_tax');
            $table->dropColumn('base_sub_total_incl_tax');
        });
    }
};
