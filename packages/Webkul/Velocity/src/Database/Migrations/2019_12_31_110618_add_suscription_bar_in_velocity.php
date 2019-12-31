<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuscriptionBarInVelocity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('velocity_meta_data', function($table) {
            $table->text('subscription_bar_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('velocity_meta_data', function($table) {
            $table->dropColumn('subscription_bar_content');
        });
    }
}
