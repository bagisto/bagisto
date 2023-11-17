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
        Schema::create('url_rewrites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_type');
            $table->string('request_path');
            $table->string('target_path');
            $table->string('redirect_type')->nullable();
            $table->string('locale');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_rewrites');
    }
};
