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
        Schema::create('product_customizable_option_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale');
            $table->text('label')->nullable();
            $table->integer('product_customizable_option_id')->unsigned();

            $table->unique(['product_customizable_option_id', 'locale'], 'product_customizable_option_id_locale_unique');
            $table->foreign('product_customizable_option_id', 'pcot_product_customizable_option_id_foreign')
                ->references('id')
                ->on('product_customizable_options')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_customizable_option_translations');
    }
};
