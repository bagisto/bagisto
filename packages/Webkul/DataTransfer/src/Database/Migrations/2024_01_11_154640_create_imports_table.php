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
            $table->string('state')->default('pending');
            $table->boolean('process_in_queue')->default(1);
            $table->string('type');
            $table->string('action');
            $table->string('validation_strategy');
            $table->integer('allowed_errors')->default(0);
            $table->integer('processed_rows_count')->default(0);
            $table->integer('invalid_rows_count')->default(0);
            $table->integer('errors_count')->default(0);
            $table->json('errors')->nullable();
            $table->string('field_separator');
            $table->string('file_path');
            $table->string('images_directory_path')->nullable();
            $table->string('error_file_path')->nullable();
            $table->json('summary')->nullable();

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
