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
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->string('unique_id')->nullable()->unique();
        });

        DB::table('product_attribute_values')
            ->update(['unique_id' => DB::raw("CONCAT_WS('|', channel, locale, product_id, attribute_id)")]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropColumn('unique_id');
        });
    }
};
