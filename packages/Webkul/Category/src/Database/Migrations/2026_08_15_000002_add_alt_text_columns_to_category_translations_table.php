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
        Schema::table('category_translations', function (Blueprint $table) {
            if (! Schema::hasColumn('category_translations', 'logo_alt')) {
                $table->text('logo_alt')->nullable()->after('meta_keywords');
            }

            if (! Schema::hasColumn('category_translations', 'banner_alt')) {
                $table->text('banner_alt')->nullable()->after('logo_alt');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_translations', function (Blueprint $table) {
            if (Schema::hasColumn('category_translations', 'logo_alt')) {
                $table->dropColumn('logo_alt');
            }

            if (Schema::hasColumn('category_translations', 'banner_alt')) {
                $table->dropColumn('banner_alt');
            }
        });
    }
};
