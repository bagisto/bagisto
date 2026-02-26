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
        Schema::create('catalog_rule_customer_groups', function (Blueprint $table) {
            $table->integer('catalog_rule_id')->unsigned();
            $table->integer('customer_group_id')->unsigned();

            $table->primary(['catalog_rule_id', 'customer_group_id'], 'catalog_rule_id_customer_group_id_primary');
            $table->foreign('catalog_rule_id')->references('id')->on('catalog_rules')->onDelete('cascade');
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalog_rule_customer_groups');
    }
};
