<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyCodeSymbolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_code_symbol', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol')->nullable();
            $table->string('name')->nullable();
            $table->string('symbol_native')->nullable();
            $table->string('decimal_digits')->nullable();
            $table->string('rounding')->nullable();
            $table->string('code')->nullable();
            $table->string('name_plural')->nullable();
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
        Schema::dropIfExists('currency_code_symbol');
    }
}
