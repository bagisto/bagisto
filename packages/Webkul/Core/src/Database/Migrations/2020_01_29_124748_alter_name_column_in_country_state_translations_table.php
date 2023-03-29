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
        Schema::table('country_state_translations', function (Blueprint $table) {
            $table->renameColumn('name', 'default_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('country_state_translations', function (Blueprint $table) {
            $table->renameColumn('default_name', 'name');
        });
    }
};
