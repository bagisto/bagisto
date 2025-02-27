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
        Schema::table('product_flat', function (Blueprint $table) {
            $table->string('product_code')->nullable()->after('product_number');
            $table->string('manufacturer_detail')->nullable()->after('product_code');
            $table->string('packer_detail')->nullable()->after('manufacturer_detail');
            $table->string('importer_detail')->nullable()->after('packer_detail');
            $table->string('country_of_origin')->nullable()->after('importer_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->dropColumn('product_code');
            $table->dropColumn('manufacturer_detail');
            $table->dropColumn('packer_detail');
            $table->dropColumn('importer_detail');
            $table->dropColumn('country_of_origin');
        });
    }
};
