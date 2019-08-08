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
            $table->string('name');
            $table->text('description');
            $table->datetime('starts_from')->nullable();
            $table->datetime('ends_till')->nullable();
            $table->boolean('status')->default(0);
            $table->json('conditions')->nullable();
            $table->json('actions')->nullable();
            $table->boolean('end_other_rules')->default(0);
            $table->string('action_code')->nullable();
            $table->decimal('discount_amount', 20, 4)->default(0.0000);
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
