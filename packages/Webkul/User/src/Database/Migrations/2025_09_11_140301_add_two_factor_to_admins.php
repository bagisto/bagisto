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
        Schema::table('admins', function (Blueprint $table) {
            $table->text('two_factor_secret')->after('remember_token')->nullable();
            $table->boolean('two_factor_enabled')->after('two_factor_secret')->default(false);
            $table->json('two_factor_backup_codes')->after('two_factor_enabled')->nullable();
            $table->timestamp('two_factor_verified_at')->after('two_factor_backup_codes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('two_factor_verified_at');
            $table->dropColumn('two_factor_backup_codes');
            $table->dropColumn('two_factor_enabled');
            $table->dropColumn('two_factor_secret');
        });
    }
};
