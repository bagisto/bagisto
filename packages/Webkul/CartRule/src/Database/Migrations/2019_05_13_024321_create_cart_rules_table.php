<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->date('starts_from')->nullable();
            $table->date('ends_till')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('coupon_type')->default(1);
            $table->boolean('use_auto_generation')->default(0);
            $table->integer('usage_per_customer')->default(0);
            $table->integer('uses_per_coupon')->default(0);
            $table->integer('times_used')->unsigned()->default(0);
            $table->boolean('condition_type')->default(1);
            $table->json('conditions')->nullable();
            $table->boolean('end_other_rules')->default(0);
            $table->boolean('uses_attribute_conditions')->default(0);
            $table->string('action_type')->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0);
            $table->integer('discount_quantity')->default(1);
            $table->string('discount_step')->default(1);
            $table->boolean('apply_to_shipping')->default(0);
            $table->boolean('free_shipping')->default(0);
            $table->integer('sort_order')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_rule_customers', function (Blueprint $table) {
            $table->dropForeign('cart_rule_customers_cart_rule_id_foreign');
        });

        Schema::table('cart_rule_coupons', function (Blueprint $table) {
            $table->dropForeign('cart_rule_coupons_cart_rule_id_foreign');
        });

         // Schema::dropIfExists('cart_rule_coupons');

        Schema::dropIfExists('cart_rules');
    }
}
