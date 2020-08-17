<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVelocityMetaData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            $table->json('product_view_images')->nullable();
            $table->integer('bundle_product_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('velocity_meta_data', function (Blueprint $table) {
            $table->dropColumn('product_view_images');
            $table->dropColumn('bundle_product_count');
        });
    }
}
