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
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->decimal('price_incl_tax', 12, 4)->default(0)->after('base_total');
            $table->decimal('base_price_incl_tax', 12, 4)->default(0)->after('price_incl_tax');
        });

        DB::table('shipment_items')->update([
            'price_incl_tax'      => DB::raw('price'),
            'base_price_incl_tax' => DB::raw('base_price'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->dropColumn('price_incl_tax');
            $table->dropColumn('base_price_incl_tax');
        });
    }
};
