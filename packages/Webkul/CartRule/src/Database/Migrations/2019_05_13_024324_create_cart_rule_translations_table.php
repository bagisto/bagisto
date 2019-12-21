<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartRuleTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_rule_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->text('label')->nullable();
            $table->integer('cart_rule_id')->unsigned();
            $table->unique(['cart_rule_id', 'locale']);
            $table->foreign('cart_rule_id')->references('id')->on('cart_rules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_rule_translations');
    }
}
