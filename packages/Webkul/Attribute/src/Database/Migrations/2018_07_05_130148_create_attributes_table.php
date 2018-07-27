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
            $table->string('admin_name');
            $table->string('type');
            $table->string('validation')->nullable();
            $table->integer('position')->nullable();
            $table->boolean('is_required')->default(0);
            $table->boolean('is_unique')->default(0);
            $table->boolean('value_per_locale')->default(0);
            $table->boolean('value_per_channel')->default(0);
            $table->boolean('is_filterable')->default(0);
            $table->boolean('is_configurable')->default(0);
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
