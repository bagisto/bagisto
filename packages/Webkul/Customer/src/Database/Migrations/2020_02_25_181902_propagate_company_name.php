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
        Schema::table('cart_address', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('vat_id')->nullable()->after('company_name');
        });

        Schema::table('order_address', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('email');
            $table->string('vat_id')->nullable()->after('company_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_address', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('vat_id');
        });

        Schema::table('order_address', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('vat_id');
        });
    }
};
