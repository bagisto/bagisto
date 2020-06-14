<?php

namespace Tests\Acceptance\BookingProduct;

use AcceptanceTester;
use Carbon\Carbon;
use Faker\Factory;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductEventTicket;
use Webkul\Core\Helpers\Laravel5Helper;
use Webkul\Product\Models\Product;

class BookingProductEventTicketCest
{
    protected $faker;

    public function _before(): void
    {
        $this->faker = Factory::create();
    }

    public function testSpecialPricesAreShown(AcceptanceTester $I): void
    {
        $product = $I->haveProduct(Laravel5Helper::VIRTUAL_PRODUCT);
        Product::query()->where('id', $product->id)->update(['type' => 'booking']);

        $bookingProduct = $I->have(BookingProduct::class, [
            'type'         => 'event',
            'available_to' => Carbon::now()->addMinutes($this->faker->numberBetween(2, 59))->toDateTimeString(),
            'product_id'   => $product->id,
        ]);

        $scenario['ticket'] = [
            'price' => 10,
            'special_price' => 5
        ];

        $ticket = $I->have(BookingProductEventTicket::class, array_merge(
                ['booking_product_id' => $bookingProduct->id], $scenario['ticket'])
        );

        $I->amOnPage($product->url_key);

        $I->see(core()->currency($ticket->price), '//span[@class="regular-price"]');
        $I->see(__('bookingproduct::app.shop.products.per-ticket-price', ['price' => core()->currency($ticket->special_price)]),
                '//span[@class="special-price"]');

    }
}