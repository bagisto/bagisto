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

            OrderAddress::query()
                ->orderBy('id', 'asc') // for some reason each() needs an orderBy in before
                ->each(static function ($row) {
                    Invoice::query()
                        ->where('order_address_id', $row->additional['old_order_address_id'])
                        ->update(['order_address_id' => $row->id]);
                });
        });

        Schema::table('shipments', static function (Blueprint $table) {
            $table->dropForeign(['order_address_id']);

            OrderAddress::query()
                ->orderBy('id', 'asc') // for some reason each() needs an orderBy in before
                ->each(static function ($row) {
                    Shipment::query()
                        ->where('order_address_id', $row->additional['old_order_address_id'])
                        ->update(['order_address_id' => $row->id]);
                });
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
