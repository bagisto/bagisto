<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_map', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('tax_rule_id')->unsigned();

            $table->foreign('tax_rule_id')->references('id')->on('tax_rules')->onDelete('cascade');

            $table->integer('tax_rate_id')->unsigned();

            $table->foreign('tax_rate_id')->references('id')->on('tax_rates')->onDelete('cascade');

            $table->unique(['tax_rule_id', 'tax_rate_id'], 'tax_map_index_unique');

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
        Schema::dropIfExists('tax_map');
    }
}
