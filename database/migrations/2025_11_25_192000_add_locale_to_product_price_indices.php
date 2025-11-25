<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_price_indices', function (Blueprint $table) {
            // Add column ONLY if missing (avoids migration failure)
            if (!Schema::hasColumn('product_price_indices', 'locale')) {
                $table->string('locale', 5)->default('en')->after('channel_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_price_indices', function (Blueprint $table) {
            if (Schema::hasColumn('product_price_indices', 'locale')) {
                $table->dropColumn('locale');
            }
        });
    }
};
