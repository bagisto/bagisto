<?php

namespace Tests\Unit\Core\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use UnitTester;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductEventTicket;
use Webkul\Core\Helpers\Laravel5Helper;
use Webkul\Product\Models\Product;

class BookingCronCest
{
    public function testBookingCronDeactivateSomeEvents(UnitTester $I): void
    {
        $index = $I->fake()->numberBetween(2, 6);

        for ($i=0; $i<$index; $i++) {
            $products[$i] = $I->haveProduct(Laravel5Helper::VIRTUAL_PRODUCT);
            Product::query()->where('id', $products[$i]->id)->update(['type' => 'booking']);

            if ($I->fake()->randomDigitNotNull <= 5) {
                $availableTo = Carbon::now()->subMinutes($I->fake()->numberBetween(2, 59));
            } else {
                $availableTo = Carbon::now()->addMinutes($I->fake()->numberBetween(2, 59));
            }

            $bookingProducts[$i] = $I->have(BookingProduct::class, [
                'type'         => 'event',
                'available_to' => $availableTo->toDateTimeString(),
                'product_id'   => $products[$i]->id,
            ]);

            $I->have(BookingProductEventTicket::class,
                ['booking_product_id' => $bookingProducts[$i]->id]);

            $products[$i]->refresh();
            $I->assertNotFalse($products[$i]->status);
        }

        $I->callArtisan('booking:cron');

        for ($i=0; $i<$index; $i++) {
            $products[$i]->refresh();

            if ($bookingProducts[$i]->available_to < Carbon::now()) {
                $I->assertEquals(0, $products[$i]->status);
            } else {
                $I->assertEquals(1, $products[$i]->status);
            }
        }
    }
}

