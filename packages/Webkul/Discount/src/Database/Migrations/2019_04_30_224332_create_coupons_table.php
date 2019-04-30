<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('auto_generate')->default(0);
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->integer('spaces_per_characters')->default(4);
            $table->integer('discount_id');
            $table->foreign('discount_id')->references('id')->on('discount')->onDelete('cascade');
            $table->integer('allowed_uses')->unsigned();
            $table->integer('allowed_uses_per_customer')->unsigned();
            $table->timestamps();
        });

        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->boolean('status')->default(0);
            $table->integer('coupon_id');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
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
        Schema::dropIfExists('coupons');

        Schema::dropIfExists('coupon_codes');
    }
}
