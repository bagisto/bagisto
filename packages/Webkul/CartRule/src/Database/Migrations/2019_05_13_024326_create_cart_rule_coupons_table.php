<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartruleCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_rule_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->integer('usage_limit')->unsigned()->default(0);
            $table->integer('usage_per_customer')->unsigned()->default(0);
            $table->integer('times_used')->unsigned()->default(0);
            $table->integer('type')->unsigned()->default(0);
            $table->boolean('is_primary')->default(0);
            $table->date('expired_at')->nullable();
            
            $table->integer('cart_rule_id')->unsigned();
            $table->foreign('cart_rule_id')->references('id')->on('cart_rules')->onDelete('cascade');
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
        Schema::dropIfExists('cartrule_coupons');
    }
}
