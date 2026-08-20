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
        Schema::table('theme_sections', function (Blueprint $table) {
            $table->boolean('draft_status')->nullable()->after('status');

            $table->integer('draft_sort_order')->nullable()->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_sections', function (Blueprint $table) {
            $table->dropColumn(['draft_status', 'draft_sort_order']);
        });
    }
};
