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
        Schema::table('currencies', function (Blueprint $table) {
            $table->string('group_separator')->default(',')->after('decimal');
            $table->string('decimal_separator')->default('.')->after('group_separator');
            $table->string('currency_position')->nullable()->after('decimal_separator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('currency_position');
            $table->dropColumn('decimal_separator');
            $table->dropColumn('group_separator');
        });
    }
};
