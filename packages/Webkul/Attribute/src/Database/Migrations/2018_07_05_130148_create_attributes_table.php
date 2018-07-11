<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type');
            $table->string('validation')->nullable();
            $table->integer('position')->nullable();
            $table->boolean('is_required');
            $table->boolean('is_unique');
            $table->boolean('value_per_locale');
            $table->boolean('value_per_channel');
            $table->boolean('is_filterable');
            $table->boolean('is_configurable');
            $table->boolean('is_user_defined')->default(1);
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
        Schema::dropIfExists('attributes');
    }
}
