<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->string('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->boolean('status')->default(0);
            $table->dateTime('starts_from');
            $table->dateTime('ends_till');
            $table->integer( 'priority')->unsigned();
            $table->boolean('ends_subsequent')->default(false);
            $table->boolean('is_all')->default(1);
        });

        Schema::table('discount_channels', function (Blueprint $table) {
            $table->integer('discount_id')->unsigned();
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->integer('channel_id')->unsigned();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });

        Schema::table('discount_customer_group', function (Blueprint $table) {
            $table->integer('discount_id')->unsigned();
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->integer('customer_group_id')->unsigned();
            $table->foreign('customer_group_id')->references('id')->on('customer_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('type');
            $table->dropColumn('description');
            $table->dropColumn( 'status');
            $table->dropColumn( 'starts_from');
        });

        Schema::dropIfExists('discount_channels');

        Schema::dropIfExists('discount_customer_group');
    }
}
