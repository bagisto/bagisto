<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Theme customizations are sections
|--------------------------------------------------------------------------
|
| Renames the theme customization tables and their foreign key to sections.
|
| Guarded on both sides so that it is safe on a fresh database, where the earlier
| migrations have just created the old names, and on an installed one.
|
*/
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (
            Schema::hasTable('theme_customizations')
            && ! Schema::hasTable('sections')
        ) {
            Schema::rename('theme_customizations', 'sections');
        }

        if (
            Schema::hasTable('theme_customization_translations')
            && ! Schema::hasTable('section_translations')
        ) {
            Schema::rename('theme_customization_translations', 'section_translations');
        }

        if (
            Schema::hasTable('section_translations')
            && Schema::hasColumn('section_translations', 'theme_customization_id')
        ) {
            Schema::table('section_translations', function (Blueprint $table) {
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
            Schema::hasTable('section_translations')
            && Schema::hasColumn('section_translations', 'section_id')
        ) {
            Schema::table('section_translations', function (Blueprint $table) {
                $table->renameColumn('section_id', 'theme_customization_id');
            });
        }

        if (
            Schema::hasTable('section_translations')
            && ! Schema::hasTable('theme_customization_translations')
        ) {
            Schema::rename('section_translations', 'theme_customization_translations');
        }

        if (
            Schema::hasTable('sections')
            && ! Schema::hasTable('theme_customizations')
        ) {
            Schema::rename('sections', 'theme_customizations');
        }
    }
};
