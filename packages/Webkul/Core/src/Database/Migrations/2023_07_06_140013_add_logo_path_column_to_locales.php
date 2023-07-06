<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('locales', function (Blueprint $table) {
            $table->string('logo_path')->after('direction')->nullable();
        });

        DB::table('locales')->whereNull('logo_path')->update(['logo_path'=> DB::raw('CONCAT("locale/", code, ".png")')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locales', function (Blueprint $table) {
            $table->dropColumn('logo_path');
        });
    }
};
