<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->integer('position');
            $table->integer('attribute_family_id')->unsigned();
            $table->unique(['attribute_family_id', 'name']);
        });

        Schema::create('attribute_group_mappings', function (Blueprint $table) {
            $table->integer('attribute_id')->unsigned();
            $table->integer('attribute_group_id')->unsigned();
            $table->primary(['attribute_id', 'attribute_group_id']);
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->foreign('attribute_group_id')->references('id')->on('attribute_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_groups');

        Schema::dropIfExists('attribute_group_mappings');
    }
}
