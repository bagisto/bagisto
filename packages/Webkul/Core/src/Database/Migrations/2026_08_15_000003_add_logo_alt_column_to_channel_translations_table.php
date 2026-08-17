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
        Schema::table('channel_translations', function (Blueprint $table) {
            if (! Schema::hasColumn('channel_translations', 'logo_alt')) {
                $table->text('logo_alt')->nullable()->after('maintenance_mode_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('channel_translations', function (Blueprint $table) {
            if (Schema::hasColumn('channel_translations', 'logo_alt')) {
                $table->dropColumn('logo_alt');
            }
        });
    }
};
