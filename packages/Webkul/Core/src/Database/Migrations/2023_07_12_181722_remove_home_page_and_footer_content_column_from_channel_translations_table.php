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
        Schema::table('channel_translations', function (Blueprint $table) {
            $table->dropColumn('home_page_content');
            $table->dropColumn('footer_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_translations', function (Blueprint $table) {
            $table->text('home_page_content')->nullable()->after('description');
            $table->text('footer_content')->nullable()->after('home_page_content');
        });
    }
};
