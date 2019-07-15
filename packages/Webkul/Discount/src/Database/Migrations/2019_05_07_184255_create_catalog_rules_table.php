<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_guest')->default(0);
            $table->datetime('starts_from')->nullable();
            $table->datetime('ends_till')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('priority')->unsigned()->default(0);
            $table->json('conditions')->nullable();
            $table->string('test_mode')->nullable();
            $table->json('actions')->nullable();
            $table->boolean('end_other_rules')->default(0);
            $table->integer('sort_order')->unsigned()->default(0);
            $table->string('action_type')->nullable();
            $table->decimal('disc_amount', 12, 4)->default(0);
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
        Schema::dropIfExists('catalog_rules');
    }
}
