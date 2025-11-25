<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('attributes')
          ->where('code', 'price')
          ->update(['value_per_locale' => 1]);
    }

    public function down(): void
    {
        DB::table('attributes')
          ->where('code', 'price')
          ->update(['value_per_locale' => 0]);
    }
};
