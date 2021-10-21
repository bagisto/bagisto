<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemoveCatalogRuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('catalog_rule_customer_groups');

        Schema::dropIfExists('catalog_rule_channels');

        Schema::dropIfExists('catalog_rule_products');

        Schema::dropIfExists('catalog_rule_products_price');

        Schema::dropIfExists('catalog_rules');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
