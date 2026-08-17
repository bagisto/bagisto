<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Webkul\RMA\Enums\DefaultRMAStatusEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Rename the default "Received Package" terminal status to the clearer
     * "Refunded", since selecting it actually triggers the order refund.
     * Only the untouched default title is updated so custom titles are kept.
     */
    public function up(): void
    {
        DB::table('rma_statuses')
            ->where('id', DefaultRMAStatusEnum::RECEIVED_PACKAGE->value)
            ->where('title', 'Received Package')
            ->update(['title' => 'Refunded']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('rma_statuses')
            ->where('id', DefaultRMAStatusEnum::RECEIVED_PACKAGE->value)
            ->where('title', 'Refunded')
            ->update(['title' => 'Received Package']);
    }
};
