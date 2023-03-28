<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::unprepared('DROP FUNCTION IF EXISTS get_url_path_of_category;');
        DB::unprepared('DROP TRIGGER IF EXISTS trig_categories_insert;');
        DB::unprepared('DROP TRIGGER IF EXISTS trig_categories_update;');
        DB::unprepared('DROP TRIGGER IF EXISTS trig_category_translations_insert;');
        DB::unprepared('DROP TRIGGER IF EXISTS trig_category_translations_update;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
