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
        Schema::create('rma_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->boolean('status')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_reasons');
    }
};
