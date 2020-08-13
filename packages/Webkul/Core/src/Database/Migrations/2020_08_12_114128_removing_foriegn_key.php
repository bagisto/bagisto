<?php

use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Shipment;
use Webkul\Sales\Models\OrderAddress;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovingForiegnKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', static function (Blueprint $table) {
            $table->dropForeign(['order_address_id']);
        });

        Schema::table('shipments', static function (Blueprint $table) {
            $table->dropForeign(['order_address_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
