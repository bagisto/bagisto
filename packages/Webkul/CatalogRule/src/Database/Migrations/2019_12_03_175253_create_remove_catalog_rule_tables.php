<?php

use Illuminate\Database\Migrations\Migration;
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
};
