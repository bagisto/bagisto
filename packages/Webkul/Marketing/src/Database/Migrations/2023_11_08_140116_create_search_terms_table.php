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
        Schema::create('search_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('term');
            $table->integer('results')->default(0);
            $table->integer('uses')->default(0);
            $table->string('redirect_url')->nullable();
            $table->boolean('display_in_suggested_terms')->default(0);
            $table->string('locale');

            $table->integer('channel_id')->unsigned();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_terms');
    }
};
