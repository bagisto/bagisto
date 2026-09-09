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
        Schema::table('categories', function (Blueprint $table) {
            if (! Schema::hasColumn('categories', 'mobile_logo_path')) {
                $table->text('mobile_logo_path')->nullable()->after('logo_path');
            }

            if (! Schema::hasColumn('categories', 'mobile_banner_path')) {
                $table->text('mobile_banner_path')->nullable()->after('banner_path');
            }
        });

        Schema::table('category_translations', function (Blueprint $table) {
            if (! Schema::hasColumn('category_translations', 'mobile_logo_alt')) {
                $table->text('mobile_logo_alt')->nullable()->after('banner_alt');
            }

            if (! Schema::hasColumn('category_translations', 'mobile_banner_alt')) {
                $table->text('mobile_banner_alt')->nullable()->after('mobile_logo_alt');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_translations', function (Blueprint $table) {
            if (Schema::hasColumn('category_translations', 'mobile_banner_alt')) {
                $table->dropColumn('mobile_banner_alt');
            }

            if (Schema::hasColumn('category_translations', 'mobile_logo_alt')) {
                $table->dropColumn('mobile_logo_alt');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'mobile_banner_path')) {
                $table->dropColumn('mobile_banner_path');
            }

            if (Schema::hasColumn('categories', 'mobile_logo_path')) {
                $table->dropColumn('mobile_logo_path');
            }
        });
    }
};
