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
        Schema::table('product_customer_group_prices', function (Blueprint $table) {
            $table->string('unique_id')->nullable()->unique();
        });

        DB::table('product_customer_group_prices')
            ->update(['unique_id' => DB::raw("CONCAT_WS('|', qty, product_id, customer_group_id)")]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_customer_group_prices', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });
    }
};
