<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomerAddressImprovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('customer_id');
            $table->string('vat_id')->nullable()->after('company_name');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_company_name')->nullable()->after('customer_last_name');
            $table->string('customer_vat_id')->nullable()->after('customer_company_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('vat_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('customer_company_name');
            $table->dropColumn('customer_vat_id');
        });
    }
}
