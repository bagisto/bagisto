<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('locales')->insertOrIgnore([
            'code' => 'vi',
            'name' => 'Tiếng Việt',
            'direction' => 'ltr',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('locales')->where('code', 'vi')->delete();
    }
};
