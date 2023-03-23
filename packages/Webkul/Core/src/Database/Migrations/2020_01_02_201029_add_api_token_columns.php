<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // @see https://laravel.com/docs/6.x/api-authentication#database-preparation

        Schema::table('customers', function ($table) {
            $table
                ->string('api_token', 80)
                ->after('password')
                ->unique()
                ->nullable()
                ->default(null);
        });

        Schema::table('admins', function ($table) {
            $table
                ->string('api_token', 80)
                ->after('password')
                ->unique()
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
};
