<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Draft options for the section editor
|--------------------------------------------------------------------------
|
| Holds edits that are not published yet, so the editor can preview them while the
| storefront keeps rendering `options`. A null draft means there is nothing pending.
|
*/
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('section_translations', function (Blueprint $table) {
            if (! Schema::hasColumn('section_translations', 'draft_options')) {
                $table->json('draft_options')->nullable()->after('options');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('section_translations', function (Blueprint $table) {
            if (Schema::hasColumn('section_translations', 'draft_options')) {
                $table->dropColumn('draft_options');
            }
        });
    }
};
