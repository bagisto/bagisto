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
        Schema::table('product_categories', function (Blueprint $table) {
            $table->unique(['product_id', 'category_id']);
        });

        Schema::table('product_relations', function (Blueprint $table) {
            $table->unique(['parent_id', 'child_id']);
        });

        Schema::table('product_cross_sells', function (Blueprint $table) {
            $table->unique(['parent_id', 'child_id']);
        });

        Schema::table('product_up_sells', function (Blueprint $table) {
            $table->unique(['parent_id', 'child_id']);
        });

        Schema::table('product_grouped_products', function (Blueprint $table) {
            $table->unique(['product_id', 'associated_product_id'], 'grouped_products_product_id_associated_product_id_unique');
        });

        Schema::table('product_bundle_option_translations', function (Blueprint $table) {
            $table->string('label')->nullable()->change();

            $table->unique(['locale', 'label', 'product_bundle_option_id'], 'bundle_option_translations_locale_label_bundle_option_id_unique');
        });

        Schema::table('product_bundle_option_products', function (Blueprint $table) {
            $table->unique(['product_id', 'product_bundle_option_id'], 'bundle_option_products_product_id_bundle_option_id_unique');
        });

        Schema::table('product_super_attributes', function (Blueprint $table) {
            $table->unique(['product_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_super_attributes', function (Blueprint $table) {
            $table->dropForeign(['product_id']);

            $table->dropUnique(['product_id', 'attribute_id']);
        });

        Schema::table('product_bundle_option_products', function (Blueprint $table) {
            $table->dropForeign(['product_id']);

            $table->dropUnique('bundle_option_products_product_id_bundle_option_id_unique');
        });

        Schema::table('product_bundle_option_translations', function (Blueprint $table) {
            $table->dropForeign('product_bundle_option_translations_option_id_foreign');

            $table->dropUnique('bundle_option_translations_locale_label_bundle_option_id_unique');

            $table->string('label')->change();
        });

        Schema::table('product_grouped_products', function (Blueprint $table) {
            $table->dropForeign(['product_id']);

            $table->dropUnique('grouped_products_product_id_associated_product_id_unique');
        });

        Schema::table('product_up_sells', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);

            $table->dropUnique(['parent_id', 'child_id']);
        });

        Schema::table('product_cross_sells', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);

            $table->dropUnique(['parent_id', 'child_id']);
        });

        Schema::table('product_relations', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);

            $table->dropUnique(['parent_id', 'child_id']);
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['product_id']);

            $table->dropUnique(['product_id', 'category_id']);
        });
    }
};
