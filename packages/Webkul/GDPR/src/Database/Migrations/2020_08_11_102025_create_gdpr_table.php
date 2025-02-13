<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGdprTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gdpr', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('gdpr_status');
            $table->boolean('customer_agreement_status');
            $table->string('agreement_label')->nullable();
            $table->longText('agreement_content')->nullable();
            $table->boolean('cookie_status');
            $table->string('cookie_block_position');
            $table->string('cookie_static_block_identifier')->nullable();
            $table->longText('strictly_necessary_cookie')->nullable();
            $table->longText('basic_interactions_and_functionalities_cookie')->nullable();
            $table->longText('experience_enhancement_cookie')->nullable();
            $table->longText('measurement_cookie')->nullable();
            $table->longText('targeting_and_advertising_cookie')->nullable();
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
        Schema::dropIfExists('gdpr');
    }
}
