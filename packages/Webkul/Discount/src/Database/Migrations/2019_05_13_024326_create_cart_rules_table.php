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
            $table->json('conditions')->nullable();
            $table->json('actions')->nullable();
            $table->longtext('product_ids')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('end_other_rules')->default(1);
            $table->integer('sort_order')->unsigned()->default(0);
            $table->string('action_type')->nullable();
            $table->decimal('dis_amt', 12, 4)->default(0.0000);
            $table->decimal('dis_qty', 12, 4)->nullable();
            $table->string('disc_threshold')->default(0);
            $table->integer('usage_throttle')->unsigned()->default(0);
            $table->boolean('apply_to_shipping')->default(0);
            $table->unsignedSmallInteger('coupon_type')->default(1);
            $table->boolean('auto_generation')->default(0);
            $table->unsignedSmallInteger('free_shipping_condition')->default(0);
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
