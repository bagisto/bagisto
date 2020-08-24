<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('channel_id')->unsigned();

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');

            $table->string('code')->unique();

            $table->string('name')->unique();

            $table->longtext('description');

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
        Schema::dropIfExists('tax_categories');
    }
}
