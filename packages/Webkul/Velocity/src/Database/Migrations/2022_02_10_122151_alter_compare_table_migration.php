<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompareTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('velocity_customer_compare_products', function (Blueprint $table) {
            $table->string('device_browser_uuid',100)->nullable()->after('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('velocity_customer_compare_products', function (Blueprint $table) {
            $table->dropColumn('device_browser_uuid');
        });
    }
}
