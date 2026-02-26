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
        Schema::table('shipments', function (Blueprint $table) {
            $table->decimal('total_weight', 12, 4)->nullable()->change();
        });

        Schema::table('shipment_items', function (Blueprint $table) {
            $table->decimal('weight', 12, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipment_items', function (Blueprint $table) {
            $table->integer('weight')->nullable()->change();
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->integer('total_weight')->nullable()->change();
        });
    }
};
