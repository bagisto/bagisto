<?php

use Illuminate\Database\Migrations\Migration;
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
            $table->dropColumn('thumbnail');
            $table->dropColumn('cost');
            $table->dropColumn('color');
            $table->dropColumn('color_label');
            $table->dropColumn('size');
            $table->dropColumn('size_label');
            $table->dropColumn('min_price');
            $table->dropColumn('max_price');
            $table->dropColumn('width');
            $table->dropColumn('height');
            $table->dropColumn('depth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->string('thumbnail')->nullable();
            $table->decimal('cost', 12, 4)->nullable();
            $table->integer('color')->nullable();
            $table->string('color_label')->nullable();
            $table->integer('size')->nullable();
            $table->string('size_label')->nullable();
            $table->decimal('min_price', 12, 4)->nullable();
            $table->decimal('max_price', 12, 4)->nullable();
            $table->decimal('width', 12, 4)->nullable();
            $table->decimal('height', 12, 4)->nullable();
            $table->decimal('depth', 12, 4)->nullable();
        });
    }
};
