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
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address_type');
            $table->unsignedInteger('customer_id')->nullable()->comment('null if guest checkout');
            $table->unsignedInteger('cart_id')->nullable()->comment('only for cart_addresses');
            $table->unsignedInteger('order_id')->nullable()->comment('only for order_addresses');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address1');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('vat_id')->nullable();
            $table->boolean('default_address')->default(false)->comment('only for customer_addresses');
            $table->json('additional')->nullable();
            $table->timestamps();

            $table->foreign(['customer_id'])->references('id')->on('customers')->onDelete('cascade');
            $table->foreign(['cart_id'])->references('id')->on('cart')->onDelete('cascade');
            $table->foreign(['order_id'])->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
