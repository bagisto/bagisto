<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('discount_customer_group');
        Schema::dropIfExists('discount_channels');
        Schema::dropIfExists('discount_rules');
        Schema::dropIfExists('discounts');

        Schema::create('catalog_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->datetime('starts_from');
            $table->datetime('ends_till');
            $table->json('conditions');
            $table->json('actions');
            $table->boolean('ends_other_rules');
            $table->integer('sort_order')->unsigned();
            $table->string('action_type');
            $table->string('discount_amount');
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
        Schema::dropIfExists('catalog_rules');
    }
}
