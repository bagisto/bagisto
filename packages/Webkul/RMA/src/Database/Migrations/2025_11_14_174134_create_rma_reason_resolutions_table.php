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
        Schema::create('rma_reason_resolutions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rma_reason_id')->unsigned();
            $table->string('resolution_type');
            $table->timestamps();

            $table->foreign('rma_reason_id')->references('id')->on('rma_reasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_reason_resolutions');
    }
};
