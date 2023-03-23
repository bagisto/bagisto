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
        Schema::create('tax_categories_tax_rates', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tax_category_id')->unsigned();

            $table->foreign('tax_category_id')->references('id')->on('tax_categories')->onDelete('cascade');

            $table->integer('tax_rate_id')->unsigned();

            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->onDelete('cascade');

            $table->unique(['tax_category_id', 'tax_rate_id'], 'tax_map_index_unique');

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
        Schema::dropIfExists('tax_categories_tax_rates');
    }
};
