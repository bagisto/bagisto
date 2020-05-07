<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportNewProductsByAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_new_products_by_admin', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('attribute_family_id')->unsigned();
            $table->foreign('attribute_family_id', 'mp_import_admin_foreign_attribute_family_id')->references('id')->on('attribute_families')->onDelete('cascade');

            $table->integer('data_flow_profile_id')->unsigned();
            $table->foreign('data_flow_profile_id', 'mp_import_admin_foreign_data_flow_profile_id')->references('id')->on('bulkupload_dataflowprofile')->onDelete('cascade');

            $table->boolean('is_downloadable')->default(0);
            $table->string('upload_link_files');

            $table->boolean('is_links_have_samples')->default(0);
            $table->string('upload_link_sample_files');

            $table->boolean('is_samples_available')->default(0);
            $table->string('upload_sample_files');

            $table->string('file_path');
            $table->string('image_path');

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
        Schema::dropIfExists('import_new_products_by_admin');
    }
}
