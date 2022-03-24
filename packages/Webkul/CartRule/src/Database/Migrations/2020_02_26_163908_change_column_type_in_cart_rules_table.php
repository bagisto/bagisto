<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeInCartRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_rules', function (Blueprint $table) {
            $table->dateTime('starts_from')->change();
            $table->dateTime('ends_till')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_rules', function (Blueprint $table) {
            $table->dateTime('starts_from')->change();
            $table->dateTime('ends_till')->change();
        });
    }
}
