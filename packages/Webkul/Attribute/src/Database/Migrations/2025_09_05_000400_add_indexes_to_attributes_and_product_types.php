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
        Schema::table('attributes', function (Blueprint $table) {
            if (! Schema::hasIndex('attributes', $table->getPrefix().'attributes_code_index')) {
                $table->index('code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            if (Schema::hasIndex('attributes', $table->getPrefix().'attributes_code_index')) {
                $table->dropIndex(['code']);
            }
        });
    }
};
