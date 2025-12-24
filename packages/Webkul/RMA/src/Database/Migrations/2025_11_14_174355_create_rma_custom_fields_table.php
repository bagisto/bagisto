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
        Schema::create('rma_custom_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status')->nullable()->default('0');
            $table->string('code')->unique();
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->boolean('is_required')->nullable()->default('0');
            $table->integer('position')->nullable()->default('0');
            $table->string('input_validation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rma_custom_fields');
    }
};
