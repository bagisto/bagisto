<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartRuleTable extends Migration
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
            $table->datetime('starts_from')->nullable();
            $table->datetime('ends_till')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('per_customer')->unsigned()->default(0);
            $table->boolean('is_guest')->default(0);
            $table->boolean('use_coupon')->default(0);
            $table->integer('usage_limit')->unsigned()->default(0);
            $table->json('conditions')->nullable();
            $table->string('test_mode')->nullable();
            $table->json('actions')->nullable();
            $table->boolean('end_other_rules')->default(0);
            $table->integer('priority')->unsigned()->default(0);
            $table->boolean('uses_attribute_conditions')->default(0);
            $table->longtext('product_ids')->nullable();
            $table->integer('sort_order')->unsigned()->default(0);
            $table->string('action_type')->nullable();
            $table->decimal('disc_amount', 12, 4)->default(0);
            $table->decimal('disc_quantity', 12, 4)->default(0);
            $table->string('disc_threshold')->default(0);
            $table->integer('coupon_type')->default(1);
            $table->boolean('auto_generation')->default(0);
            $table->boolean('apply_to_shipping')->default(0);
            $table->boolean('free_shipping')->default(0);
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
        Schema::dropIfExists('cart_rule');
    }
}
