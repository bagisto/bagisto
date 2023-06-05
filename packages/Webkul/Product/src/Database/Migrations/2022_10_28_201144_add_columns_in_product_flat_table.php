<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->string('type')->after('sku')->nullable();
            $table->integer('attribute_family_id')->unsigned()->before('product_id')->nullable();

            $table->foreign('attribute_family_id')->references('id')->on('attribute_families')->onDelete('restrict');
        });

        DB::table('product_flat')
            ->join('products', 'product_flat.product_id', '=', 'products.id')
            ->update([
                'product_flat.type' => DB::raw('products.type'),
                'product_flat.attribute_family_id' => DB::raw('products.attribute_family_id')
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->dropForeign(['attribute_family_id']);

            $table->dropColumn('type');
            $table->dropColumn('attribute_family_id');
        });
    }
};
