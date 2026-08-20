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
        if (
            Schema::hasTable('theme_customizations')
            && ! Schema::hasTable('theme_sections')
        ) {
            Schema::rename('theme_customizations', 'theme_sections');
        }

        if (
            Schema::hasTable('theme_customization_translations')
            && ! Schema::hasTable('theme_section_translations')
        ) {
            Schema::rename('theme_customization_translations', 'theme_section_translations');
        }

        if (
            Schema::hasTable('theme_section_translations')
            && Schema::hasColumn('theme_section_translations', 'theme_customization_id')
        ) {
            Schema::table('theme_section_translations', function (Blueprint $table) {
                $table->renameColumn('theme_customization_id', 'section_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('theme_section_translations')
            && Schema::hasColumn('theme_section_translations', 'section_id')
        ) {
            Schema::table('theme_section_translations', function (Blueprint $table) {
                $table->renameColumn('section_id', 'theme_customization_id');
            });
        }

        if (
            Schema::hasTable('theme_section_translations')
            && ! Schema::hasTable('theme_customization_translations')
        ) {
            Schema::rename('theme_section_translations', 'theme_customization_translations');
        }

        if (
            Schema::hasTable('theme_sections')
            && ! Schema::hasTable('theme_customizations')
        ) {
            Schema::rename('theme_sections', 'theme_customizations');
        }
    }
};
