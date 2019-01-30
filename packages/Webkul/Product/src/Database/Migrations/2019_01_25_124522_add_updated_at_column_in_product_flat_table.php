<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatedAtColumnInProductFlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->datetime('created_at')->change();
            $table->string('size_label')->change();
            $table->datetime('updated_at')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->boolean('visible_individually')->nullable();

            $table->foreign('parent_id')->references('id')->on('product_flat')->onDelete('cascade');
            $table->unique(['product_id', 'channel', 'locale'], 'product_flat_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('product_flat', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('size_label');
            $table->dropColumn('updated_at');
            $table->dropColumn('parent_id');
            $table->dropColumn('visible_individually');
            $table->dropForeign('parent_id');
            $table->dropIndex('product_flat_unique_index');
        });

        Schema::enableForeignKeyConstraints();
    }
}
