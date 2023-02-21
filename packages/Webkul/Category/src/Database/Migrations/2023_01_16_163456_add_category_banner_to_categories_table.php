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
        if (! Schema::hasColumn('categories', 'category_banner')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('category_banner')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('categories', 'category_banner')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('category_banner');
            });
        }
    }
};
