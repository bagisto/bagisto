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
        Schema::table('theme_section_translations', function (Blueprint $table) {
            if (! Schema::hasColumn('theme_section_translations', 'draft_options')) {
                $table->json('draft_options')->nullable()->after('options');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_section_translations', function (Blueprint $table) {
            if (Schema::hasColumn('theme_section_translations', 'draft_options')) {
                $table->dropColumn('draft_options');
            }
        });
    }
};
