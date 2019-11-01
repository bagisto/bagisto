<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemainingColumnInProductFlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            if (! Schema::hasColumn('product_flat', 'short_description')) {
                $table->text('short_description')->nullable();
            }
            if (! Schema::hasColumn('product_flat', 'meta_title')) {
                $table->text('meta_title')->nullable();
            }
            if (! Schema::hasColumn('product_flat', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable();
            }
            if (! Schema::hasColumn('product_flat', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (! Schema::hasColumn('product_flat', 'width')) {
                $table->decimal('width', 12, 4)->nullable();
            }
            if (! Schema::hasColumn('product_flat', 'height')) {
                $table->decimal('height', 12, 4)->nullable();
            }
            if (! Schema::hasColumn('product_flat', 'depth')) {
                $table->decimal('depth', 12, 4)->nullable();
            }
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
            if (Schema::hasColumn('product_flat', 'short_description')) {
                $table->dropColumn('short_description');
            }
            if (Schema::hasColumn('product_flat', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
            if (Schema::hasColumn('product_flat', 'meta_keywords')) {
                $table->dropColumn('meta_keywords');
            }
            if (Schema::hasColumn('product_flat', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('product_flat', 'width')) {
                $table->dropColumn('width');
            }
            if (Schema::hasColumn('product_flat', 'height')) {
                $table->dropColumn('height');
            }
            if (Schema::hasColumn('product_flat', 'depth')) {
                $table->dropColumn('depth');
            }
        });
    }
}