<?php

namespace Tests\Unit\Checkout;

use Codeception\Example;
use Exception;
use Illuminate\Support\Facades\Event;
use UnitTester;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductEventTicket;
use Webkul\Core\Helpers\Laravel5Helper;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\ProductDownloadableLink;

class CartCest
{
    private $customer;

    public function _before(UnitTester $I): void
    {
        $this->customer = $I->have(Customer::class);
    }

    /**
     * @param UnitTester $I
     * @param Example    $scenario
     *
     * @throws Exception
     *
     * @dataProvider getMergeCartScenarios
     */
    public function testMergeCart(UnitTester $I, Example $scenario): void
    {
        $product1 = $I->haveProduct($scenario['product_type1']);
        $product2 = $I->haveProduct($scenario['product_type2']);

        if ($scenario['product_type1'] === Laravel5Helper::DOWNLOADABLE_PRODUCT) {
            $downloadableLink1 = ProductDownloadableLink::query()->where('product_id', $product1->id)->firstOrFail();
            $I->assertNotNull($downloadableLink1);
        }
        if ($scenario['product_type1'] === Laravel5Helper::BOOKING_EVENT_PRODUCT) {
            $bookingProduct = BookingProduct::query()->where('product_id', $product1->id)->firstOrFail();
            $I->assertNotNull($bookingProduct);
            $bookingTicket1 = BookingProductEventTicket::query()->where('booking_product_id',
                $bookingProduct->id)->firstOrFail();
            $I->assertNotNull($bookingTicket1);
        }

        if ($scenario['product_type2'] === Laravel5Helper::DOWNLOADABLE_PRODUCT) {
            $downloadableLink2 = ProductDownloadableLink::query()->where('product_id', $product2->id)->firstOrFail();
            $I->assertNotNull($downloadableLink2);
        }
        if ($scenario['product_type2'] === Laravel5Helper::BOOKING_EVENT_PRODUCT) {
            $bookingProduct = BookingProduct::query()->where('product_id', $product2->id)->firstOrFail();
            $I->assertNotNull($bookingProduct);
            $bookingTicket2 = BookingProductEventTicket::query()->where('booking_product_id',
                $bookingProduct->id)->firstOrFail();
            $I->assertNotNull($bookingTicket2);
        }

        $I->comment("Check, I'm a guest");
        $I->assertFalse(auth()->guard('customer')->check());
        $I->assertNull(cart()->getCart());

        $data = [
            '_token'     => session('_token'),
            'quantity'   => 1,
            'product_id' => $product1->id,
        ];
        if ($scenario['product_type1'] === Laravel5Helper::DOWNLOADABLE_PRODUCT) {
            $data['links'] = [$downloadableLink1->id];
        }
        if ($scenario['product_type1'] === Laravel5Helper::BOOKING_EVENT_PRODUCT) {
            $data['booking'] = ['qty' => [$bookingTicket1->id => 1]];
        }

        $I->comment('A guest is adding a first product of type ' . $product1->type . ' to cart');
        cart()->addProduct($product1->id, $data);
        $I->assertEquals(1, cart()->getCart()->items->count());

        $I->comment('Guest is logging in...then guest is a known customer.');
        auth()->guard('customer')->onceUsingId($this->customer->id);
        Event::dispatch('customer.after.login', $this->customer['email']);
        $I->comment("Let us assume that the customer's shopping cart was empty. The individual product from the guest's shopping cart is transferred to the customer's shopping cart.");
        $I->assertEquals(1, cart()->getCart()->items->count());

        auth()->guard('customer')->logout();
        $data = [
            '_token'     => session('_token'),
            'quantity'   => 1,
            'product_id' => $product2->id,
        ];
        if ($scenario['product_type2'] === Laravel5Helper::DOWNLOADABLE_PRODUCT) {
            $data['links'] = [$downloadableLink2->id];
        }
        if ($scenario['product_type2'] === Laravel5Helper::BOOKING_EVENT_PRODUCT) {
            $data['booking'] = ['qty' => [$bookingTicket2->id => 1]];
        }

        $I->comment('Guest is adding a product of type ' . $product2->type . ' to cart.');
        cart()->addProduct($product2->id, $data);
        $I->assertEquals(1, cart()->getCart()->items->count());

        $I->comment('And will be logged in.');
        auth()->guard('customer')->onceUsingId($this->customer->id);

        Event::dispatch('customer.after.login', $this->customer['email']);
        $I->assertEquals(2, cart()->getCart()->items->count());

        auth()->guard('customer')->logout();
        $data = [
            '_token'     => session('_token'),
            'quantity'   => 2,
            'product_id' => $product1->id,
        ];
        if ($scenario['product_type1'] === Laravel5Helper::DOWNLOADABLE_PRODUCT) {
            $data['links'] = [$downloadableLink1->id];
        }
        if ($scenario['product_type1'] === Laravel5Helper::BOOKING_EVENT_PRODUCT) {
            $data['booking'] = ['qty' => [$bookingTicket1->id => 1]];
        }

        $I->comment('Again, guest is adding another product of type ' . $product1->type . '.');
        $I->assertNull(cart()->getCart());
        cart()->addProduct($product1->id, $data);
        $I->assertEquals(1, cart()->getCart()->items->count());
        $I->assertEquals(2, cart()->getCart()->items_qty);

        $I->comment('And will be logged in.');
        auth()->guard('customer')->onceUsingId($this->customer->id);

        Event::dispatch('customer.after.login', $this->customer['email']);
        $I->assertEquals(2, cart()->getCart()->items->count());
        $I->assertEquals(4, cart()->getCart()->items_qty);

        $this->cleanUp();
        $I->comment('=== DONE: Added ' . $product1->type . ' to ' . $product2->type . ' ===');
    }

    private function getMergeCartScenarios(): array
    {
        return [
            [
                'product_type1' => Laravel5Helper::SIMPLE_PRODUCT,
                'product_type2' => Laravel5Helper::SIMPLE_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::VIRTUAL_PRODUCT,
                'product_type2' => Laravel5Helper::VIRTUAL_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::SIMPLE_PRODUCT,
                'product_type2' => Laravel5Helper::VIRTUAL_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::VIRTUAL_PRODUCT,
                'product_type2' => Laravel5Helper::SIMPLE_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::DOWNLOADABLE_PRODUCT,
                'product_type2' => Laravel5Helper::DOWNLOADABLE_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::DOWNLOADABLE_PRODUCT,
                'product_type2' => Laravel5Helper::SIMPLE_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::SIMPLE_PRODUCT,
                'product_type2' => Laravel5Helper::DOWNLOADABLE_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::DOWNLOADABLE_PRODUCT,
                'product_type2' => Laravel5Helper::VIRTUAL_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::VIRTUAL_PRODUCT,
                'product_type2' => Laravel5Helper::DOWNLOADABLE_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::BOOKING_EVENT_PRODUCT,
                'product_type2' => Laravel5Helper::BOOKING_EVENT_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::BOOKING_EVENT_PRODUCT,
                'product_type2' => Laravel5Helper::SIMPLE_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::SIMPLE_PRODUCT,
                'product_type2' => Laravel5Helper::BOOKING_EVENT_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::BOOKING_EVENT_PRODUCT,
                'product_type2' => Laravel5Helper::VIRTUAL_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::VIRTUAL_PRODUCT,
                'product_type2' => Laravel5Helper::BOOKING_EVENT_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::BOOKING_EVENT_PRODUCT,
                'product_type2' => Laravel5Helper::DOWNLOADABLE_PRODUCT,
            ],
            [
                'product_type1' => Laravel5Helper::DOWNLOADABLE_PRODUCT,
                'product_type2' => Laravel5Helper::BOOKING_EVENT_PRODUCT,
            ],
        ];
    }

    private function cleanUp(): void
    {
        $cart = cart()->getCart();

        if ($cart) {
            foreach ($cart->items as $item) {
                cart()->removeItem($item->id);
            }
        }

        session()->forget('cart');

        auth()->guard('customer')->logout();

        session()->forget('cart');
    }
}