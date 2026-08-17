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
        Schema::table('attribute_option_translations', function (Blueprint $table) {
            if (! Schema::hasColumn('attribute_option_translations', 'swatch_alt')) {
                $table->text('swatch_alt')->nullable()->after('label');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_option_translations', function (Blueprint $table) {
            if (Schema::hasColumn('attribute_option_translations', 'swatch_alt')) {
                $table->dropColumn('swatch_alt');
            }
        });
    }
};
