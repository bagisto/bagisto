<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVelocityContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('velocity_contents', function (Blueprint $table) {
            $table->increments('id');

            $table->string('content_type', 100)->nullable();
            $table->integer('position')->unsigned()->nullable();
            $table->boolean('status')->default(0);
            
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
        Schema::dropIfExists('velocity_contents');
    }
}
