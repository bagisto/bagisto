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
        Schema::table('gdpr_data_request', function (Blueprint $table) {
            $table->renameColumn('request_status', 'status');

            $table->renameColumn('request_type', 'type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gdpr_data_request', function (Blueprint $table) {
            $table->renameColumn('request_status', 'status');

            $table->renameColumn('request_type', 'type');
        });
    }
};
