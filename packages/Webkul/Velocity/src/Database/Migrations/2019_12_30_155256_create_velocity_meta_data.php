<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVelocityMetaData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('velocity_meta_data', function (Blueprint $table) {
            $table->increments('id');

            $table->text('home_page_content');
            $table->text('footer_left_content');
            $table->text('footer_middle_content');
            $table->boolean('slider');

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
        Schema::dropIfExists('velocity_meta_data');
    }
}
