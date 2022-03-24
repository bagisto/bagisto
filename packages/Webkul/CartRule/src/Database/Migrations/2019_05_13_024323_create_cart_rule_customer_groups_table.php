<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartRuleCustomerGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_rule_customer_groups', function (Blueprint $table) {
            $table->integer('cart_rule_id')->unsigned();
            $table->integer('customer_group_id')->unsigned();

            
            $table->primary(['cart_rule_id', 'customer_group_id']);
            
            $table->foreign('cart_rule_id')->references('id')->on('cart_rules')->onDelete('cascade');
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
        Schema::dropIfExists('cart_rule_customer_groups');
    }
}
