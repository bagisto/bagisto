<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkuploadDataflowprofileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulkupload_dataflowprofile', function (Blueprint $table) {
            $table->increments('id');
            $table->string('profile_name');
            $table->integer('attribute_family_id')->unsigned();
            $table->foreign('attribute_family_id', 'mp_bulkupload_foreign_attribute_family_id')->references('id')->on('attribute_families')->onDelete('cascade');
            $table->boolean('is_seller')->default(0);
            $table->boolean('run_status')->default(0);
            $table->string('seller_id')->references('id')->on('customers');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulkupload_dataflowprofile');
    }
}
