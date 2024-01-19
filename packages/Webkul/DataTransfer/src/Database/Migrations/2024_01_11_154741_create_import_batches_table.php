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
        Schema::create('import_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->json('data');
            $table->json('summary')->nullable();
            $table->boolean('is_processed')->default(0);
            $table->integer('import_id')->unsigned();

            $table->foreign('import_id')->references('id')->on('imports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_batches');
    }
};
