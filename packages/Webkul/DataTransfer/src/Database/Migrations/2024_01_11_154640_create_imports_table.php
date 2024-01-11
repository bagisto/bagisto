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
        Schema::create('imports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity');
            $table->string('action');
            $table->string('summary')->nullable();
            $table->string('file_path')->nullable();
            $table->string('error_file_path')->nullable();

            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
