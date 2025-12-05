<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rma_custom_field_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rma_custom_field_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();

            $table->foreign('rma_custom_field_id')->references('id')->on('rma_custom_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_custom_field_options');
    }
};
