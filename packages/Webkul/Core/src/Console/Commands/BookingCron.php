<?php

namespace Webkul\Core\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Webkul\Attribute\Models\Attribute;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductFlat;

class BookingCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivates all expired Booking Products of type event';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expiredEvents = BookingProduct::query()
            ->join('product_flat', 'booking_products.product_id', '=', 'product_flat.product_id')
            ->where('booking_products.type', 'event')
            ->where('booking_products.available_to', '<=', Carbon::now())
            ->where('product_flat.status', 1)
            ->get();

        if (count($expiredEvents) > 0) {
            $attStatusId = Attribute::query()->select('id')
                ->where('code', 'status')
                ->first()
                ->id;

            foreach ($expiredEvents as $expEvent) {
                ProductAttributeValue::query()->where('product_id', $expEvent->product_id)
                    ->where('attribute_id', $attStatusId)
                    ->update(['boolean_value' => 0]);

                ProductFlat::query()->where('product_id', $expEvent->product_id)
                    ->update(['status' => 0]);

                Log::info('BookingCron: deactivated expired event', [
                    'booking_product_id' => $expEvent->id,
                    'product_id'         => $expEvent->product_id,
                ]);
            }
            $this->info('All expired events have been deactivated');
        } else {
            Log::info('BookingCron: Did not find any expired events to be deactivated');

            $this->info('Did not find any expired events to be deactivated');
        }
    }
}
