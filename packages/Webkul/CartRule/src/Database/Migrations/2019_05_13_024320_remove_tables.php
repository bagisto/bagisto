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
        Schema::dropIfExists('cart_rule_cart');

        Schema::dropIfExists('cart_rule_labels');

        Schema::dropIfExists('cart_rule_channels');

        Schema::dropIfExists('cart_rule_customer_groups');

        Schema::dropIfExists('cart_rule_customers');

        Schema::dropIfExists('cart_rule_coupons');

        Schema::dropIfExists('cart_rule_coupon_usage');

        Schema::dropIfExists('cart_rule_coupons_usage');

        Schema::dropIfExists('cart_rules');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
