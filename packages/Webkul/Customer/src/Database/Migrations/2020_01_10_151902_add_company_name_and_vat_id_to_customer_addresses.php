<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyNameAndVatIdToCustomerAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->string('company_name')->nullable()->before('address1');
            $table->string('vat_id')->nullable()->after('company_name');


            // split 'name' column into first_name and last_name
            $table->dropColumn('name');

            $table->string('first_name')->after('company_name');
            $table->string('last_name')->after('first_name');
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
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->string('name');
        });
    }
}
