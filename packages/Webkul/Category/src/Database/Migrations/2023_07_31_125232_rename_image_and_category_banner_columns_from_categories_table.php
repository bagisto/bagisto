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
        if (Schema::hasColumns('categories', ['image', 'category_banner'])) {
            Schema::dropColumns('categories', ['image', 'category_banner']);
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->text('logo_path')->nullable()->after('position');
            $table->text('banner_path')->nullable()->after('additional');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumns('categories', ['logo_path', 'banner_path'])) {
            Schema::dropColumns('categories', ['logo_path', 'banner_path']);
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->text('image')->nullable()->after('position');
            $table->text('category_banner')->nullable()->after('additional');
        });
    }
};
