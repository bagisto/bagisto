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
        if (! Schema::hasColumn('theme_section_translations', 'options')) {
            return;
        }

        Schema::table('theme_section_translations', function (Blueprint $table) {
            $table->json('options')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('theme_section_translations', 'options')) {
            return;
        }

        Schema::table('theme_section_translations', function (Blueprint $table) {
            $table->json('options')->nullable(false)->change();
        });
    }
};
