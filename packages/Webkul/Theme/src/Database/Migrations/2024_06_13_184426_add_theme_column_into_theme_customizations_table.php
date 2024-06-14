<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('theme_customizations', function (Blueprint $table) {
            $table->string('theme')->nullable()->after('id');
        });

        DB::table('theme_customizations')->update(['theme' => core()->getCurrentChannel()->theme]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('theme_customizations', function (Blueprint $table) {
            $table->dropColumn('theme');
        });
    }
};
