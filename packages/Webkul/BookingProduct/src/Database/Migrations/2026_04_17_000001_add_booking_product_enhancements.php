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
        Schema::table('booking_products', function (Blueprint $table) {
            $table->boolean('allow_cancellation')->default(true)->after('available_to');
        });

        Schema::table('booking_product_default_slots', function (Blueprint $table) {
            $table->boolean('allow_slot_overlap')->default(false)->after('slots');
        });

        Schema::table('booking_product_appointment_slots', function (Blueprint $table) {
            $table->boolean('allow_slot_overlap')->default(false)->after('slots');
        });

        Schema::table('booking_product_table_slots', function (Blueprint $table) {
            $table->boolean('allow_slot_overlap')->default(false)->after('slots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_products', function (Blueprint $table) {
            $table->dropColumn('allow_cancellation');
        });

        Schema::table('booking_product_default_slots', function (Blueprint $table) {
            $table->dropColumn('allow_slot_overlap');
        });

        Schema::table('booking_product_appointment_slots', function (Blueprint $table) {
            $table->dropColumn('allow_slot_overlap');
        });

        Schema::table('booking_product_table_slots', function (Blueprint $table) {
            $table->dropColumn('allow_slot_overlap');
        });
    }
};
