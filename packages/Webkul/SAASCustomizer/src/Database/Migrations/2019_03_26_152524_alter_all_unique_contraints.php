<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAllUniqueContraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Attribute package migration alterations
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropUnique('attributes_code_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['code', 'company_id'], 'attribute_company_code_unique_index');
        });

        Schema::table('attribute_families', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->dropForeign(['attribute_family_id']);
            $table->dropUnique('attribute_groups_attribute_family_id_name_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('attribute_family_id')->references('id')->on('attribute_families')->onDelete('cascade');
            $table->unique(['attribute_family_id', 'name', 'company_id'], 'attribute_family_id_name_company_id_unique_index');
        });

        // Category package migration alterations
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropUnique('category_translations_category_id_slug_locale_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique(['category_id', 'slug', 'locale', 'company_id'], 'id_slug_locale_company_id_unique_index');
        });

        // Checkout package migration alterations
        Schema::table('cart', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // Core package migration alterations
        Schema::table('channels', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('core_config', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('currencies', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('currency_exchange_rates', function(Blueprint $table) {
            $table->dropForeign(['target_currency']);
            $table->dropUnique('currency_exchange_rates_target_currency_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('target_currency')->references('id')->on('currencies')->onDelete('cascade');
            $table->unique(['target_currency', 'company_id'], 'target_currency_company_id_unique_id');
        });

        Schema::table('locales', function(Blueprint $table) {
            $table->dropUnique('locales_code_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['code', 'company_id'], 'code_company_id_unqiue_index');
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('subscribers_list', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // customer package table alterations
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_email_unique');
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['email', 'company_id']);
        });

        // Schema::table('customer_password_resets', function (Blueprint $table) {
        //     $table->integer('company_id')->unsigned()->after('email');
        //     $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        // });

        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('customer_groups', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('wishlist', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // Inventory package table alterations
        Schema::table('inventory_sources', function (Blueprint $table) {
            $table->dropUnique('inventory_sources_code_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['code', 'company_id'], 'code_company_id_unique');
        });

        // product package table alterations
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_sku_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['sku', 'company_id'], 'sku_company_id_unique');
        });

        Schema::table('product_flat', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropUnique('product_flat_unique_index');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['product_id', 'channel', 'locale', 'company_id'], 'product_flat_unique_index');
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropUnique('chanel_locale_attribute_value_index_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['channel', 'locale', 'attribute_id', 'product_id', 'company_id'], 'channel_locale_attr_id_product_id_company_unique');
        });

        Schema::table('product_inventories', function (Blueprint $table) {
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // sales package table alterations
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('order_address', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('order_payment', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // Schema::table('shipment_items', function (Blueprint $table) {
        //     $table->integer('company_id')->unsigned()->after('id');
        //     $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        // });

        // tax package table alterations
        Schema::table('tax_categories', function (Blueprint $table) {
            $table->dropUnique('tax_categories_code_unique');
            $table->dropUnique('tax_categories_name_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['name', 'company_id']);
            $table->unique(['code', 'company_id']);
        });

        Schema::table('tax_rates', function (Blueprint $table) {
            $table->dropUnique('tax_rates_identifier_unique');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['identifier', 'company_id']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}