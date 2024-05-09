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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_discount_amount');
            $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');
            $table->decimal('total_incl_tax', 12, 4)->default(0)->after('base_price_incl_tax');
            $table->decimal('base_total_incl_tax', 12, 4)->default(0)->after('total_incl_tax');
        });

        DB::table('invoice_items')->update([
            'price_incl_tax'      => DB::raw('price'),
            'base_price_incl_tax' => DB::raw('base_price'),
            'total_incl_tax'      => DB::raw('total'),
            'base_total_incl_tax' => DB::raw('base_total'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('price_incl_tax');
            $table->dropColumn('base_price_incl_tax');
            $table->dropColumn('total_incl_tax');
            $table->dropColumn('base_total_incl_tax');
        });
    }
};
