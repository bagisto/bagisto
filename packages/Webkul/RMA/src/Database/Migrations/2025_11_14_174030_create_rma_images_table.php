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
        Schema::create('rma_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rma_id')->unsigned();
            $table->string('path')->nullable();
            $table->timestamps();

            $table->foreign('rma_id')->references('id')->on('rma')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_images');
    }
};
