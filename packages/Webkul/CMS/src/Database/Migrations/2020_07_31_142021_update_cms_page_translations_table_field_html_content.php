<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hack core: Change the migration
 * Added by AuTN
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_page_translations', function (Blueprint $table) {
            $table->longtext('html_content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_page_translations', function (Blueprint $table) {
            $table->text('html_content')->nullable()->change();
        });
    }
};
