<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->increments('id');

            $table->string('identifier')->unique();

            $table->boolean('is_zip_from')->default(0);

            $table->integer('zip_from')->unsigned()->nullable();

            $table->integer('zip_to')->unsigned()->nullable();

            $table->string('state');

            $table->string('country');

            $table->float('tax_rate', 6, 4);

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
        Schema::dropIfExists('tax_rates');
    }
}
