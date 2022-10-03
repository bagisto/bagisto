<?php

namespace Tests\Unit\Checkout\Cart;

use Codeception\Example;
use Exception;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use UnitTester;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductEventTicket;
use Helper\Bagisto;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\ProductDownloadableLink;

class CartCest
{
    private $faker;
    private $simpleProduct1;
    private $simpleProduct2;
    private $virtualProduct1;
    private $virtualProduct2;
    private $downloadableProduct1;
    private $downloadableProduct2;
    private $customer;

    public function _before(UnitTester $I): void
    {
        $this->faker = Factory::create();

        $this->sessionToken = $this->faker->uuid;
        session(['_token' => $this->sessionToken]);

        $this->simpleProduct1 = $I->haveProduct(Bagisto::SIMPLE_PRODUCT);
        cart()->addProduct($this->simpleProduct1->id, [
            '_token'     => session('_token'),
            'product_id' => $this->simpleProduct1->id,
            'quantity'   => 1,
        ]);

        $this->simpleProduct2 = $I->haveProduct(Bagisto::SIMPLE_PRODUCT);
        cart()->addProduct($this->simpleProduct2->id, [
            '_token'     => session('_token'),
            'product_id' => $this->simpleProduct2->id,
            'quantity'   => 1,
        ]);

        $this->virtualProduct1 = $I->haveProduct(Bagisto::VIRTUAL_PRODUCT);
        cart()->addProduct($this->virtualProduct1->id, [
            '_token'     => session('_token'),
            'product_id' => $this->virtualProduct1->id,
            'quantity'   => 1,
        ]);

        $this->virtualProduct2 = $I->haveProduct(Bagisto::VIRTUAL_PRODUCT);
        cart()->addProduct($this->virtualProduct2->id, [
            '_token'     => session('_token'),
            'product_id' => $this->virtualProduct2->id,
            'quantity'   => 1,
        ]);

        $this->downloadableProduct1 = $I->haveProduct(Bagisto::DOWNLOADABLE_PRODUCT);

        $this->downloadableProduct2 = $I->haveProduct(Bagisto::DOWNLOADABLE_PRODUCT);

        $this->customer = $I->have(Customer::class);
    }

    public function testCartWithInactiveProducts(UnitTester $I)
    {
        $I->comment('sP1, sP2, vP1 and vP2 in cart');
        $I->assertEquals(4, count(cart()->getCart()->items));

        $I->comment('deactivate sP2');
        DB::table('product_attribute_values')
            ->where([
                'product_id'   => $this->simpleProduct2->id,
                'attribute_id' => 8 // status
            ])
            ->update(['boolean_value' => 0]);

        $this->simpleProduct2->refreshLoadedAttributeValues();

        Event::dispatch('catalog.product.update.after', $this->simpleProduct2->refresh());

        $I->assertFalse(cart()->hasError());
        $I->comment('sP2 is inactive');

        cart()->validateItems();
        $I->assertEquals(3, count(cart()->getCart()->items));

        $I->comment('add dP2 to cart');
        cart()->addProduct($this->downloadableProduct2->id, [
            '_token'     => session('_token'),
            'product_id' => $this->downloadableProduct2->id,
            'quantity'   => 1,
            'links'      => $this->downloadableProduct2->downloadable_links->pluck('id')->all(),
        ]);

        $I->assertEquals(4, count(cart()->getCart()->items));
        $I->assertFalse(cart()->hasError());

        $I->comment('deactivate dP2');
        DB::table('product_attribute_values')
            ->where([
                'product_id'   => $this->downloadableProduct2->id,
                'attribute_id' => 8 // status
            ])
            ->update(['boolean_value' => 0]);

        $this->simpleProduct2->refreshLoadedAttributeValues();

        Event::dispatch('catalog.product.update.after', $this->downloadableProduct2->refresh());

        $I->comment('add dP1 to cart, dP2 should be removed now');
        cart()->addProduct($this->downloadableProduct1->id, [
            '_token'     => session('_token'),
            'product_id' => $this->downloadableProduct1->id,
            'quantity'   => 1,
            'links'      => $this->downloadableProduct1->downloadable_links->pluck('id')->all(),
        ]);

        cart()->validateItems();
        $I->assertEquals(4, count(cart()->getCart()->items));

        $I->comment('deactivate vP2');
        DB::table('product_attribute_values')
            ->where([
                'product_id'   => $this->virtualProduct2->id,
                'attribute_id' => 8 // status
            ])
            ->update(['boolean_value' => 0]);

        $this->simpleProduct2->refreshLoadedAttributeValues();

        Event::dispatch('catalog.product.update.after', $this->virtualProduct2->refresh());

        $I->comment('change quantity of vP1, vP2 should be removed now');
        $cartItemId = $this->getCartItemIdFromProduct($this->virtualProduct1->id);
        cart()->updateItems([
            'qty' => [
                $cartItemId => 5
            ],
        ]);

        // now lets check without validating cart before
        $I->assertEquals(3, count(cart()->getCart()->items));

        $I->assertEquals(5, cart()->getCart()->items()->find($cartItemId)->quantity);
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

        if ($scenario['product_type1'] === Bagisto::DOWNLOADABLE_PRODUCT) {
            $downloadableLink1 = ProductDownloadableLink::query()->where('product_id', $product1->id)->firstOrFail();
            $I->assertNotNull($downloadableLink1);
        }
        if ($scenario['product_type1'] === Bagisto::BOOKING_EVENT_PRODUCT) {
            $bookingProduct = BookingProduct::query()->where('product_id', $product1->id)->firstOrFail();
            $I->assertNotNull($bookingProduct);
            $bookingTicket1 = BookingProductEventTicket::query()->where('booking_product_id',
                $bookingProduct->id)->firstOrFail();
            $I->assertNotNull($bookingTicket1);
        }

        if ($scenario['product_type2'] === Bagisto::DOWNLOADABLE_PRODUCT) {
            $downloadableLink2 = ProductDownloadableLink::query()->where('product_id', $product2->id)->firstOrFail();
            $I->assertNotNull($downloadableLink2);
        }
        if ($scenario['product_type2'] === Bagisto::BOOKING_EVENT_PRODUCT) {
            $bookingProduct = BookingProduct::query()->where('product_id', $product2->id)->firstOrFail();
            $I->assertNotNull($bookingProduct);
            $bookingTicket2 = BookingProductEventTicket::query()->where('booking_product_id',
                $bookingProduct->id)->firstOrFail();
            $I->assertNotNull($bookingTicket2);
        }

        $I->comment("Check, I'm a guest");
        $this->cleanUp();
        $I->assertFalse(auth()->guard('customer')->check());
        $I->assertNull(cart()->getCart());

        $data = [
            '_token'     => session('_token'),
            'quantity'   => 1,
            'product_id' => $product1->id,
        ];
        if ($scenario['product_type1'] === Bagisto::DOWNLOADABLE_PRODUCT) {
            $data['links'] = [$downloadableLink1->id];
        }
        if ($scenario['product_type1'] === Bagisto::BOOKING_EVENT_PRODUCT) {
            $data['booking'] = ['qty' => [$bookingTicket1->id => 1]];
        }

        $I->comment('A guest is adding a first product of type ' . $product1->type . ' with id ' . $product1->id . ' to cart');
        cart()->addProduct($product1->id, $data);
        $I->assertEquals(1, cart()->getCart()->items->count());

        $I->comment('Guest is logging in...then guest is a known customer.');
        auth()->guard('customer')->onceUsingId($this->customer->id);
        Event::dispatch('customer.after.login', $this->customer['email']);
        $I->comment("Let us assume that the customer's shopping cart was empty. The individual product from the guest's shopping cart is transferred to the customer's shopping cart.");
        $I->assertEquals(1, cart()->getCart()->items->count());

        auth()->guard('customer')->logout();
        cart()->setCart(null);

        $data = [
            '_token'     => session('_token'),
            'quantity'   => 1,
            'product_id' => $product2->id,
        ];
        if ($scenario['product_type2'] === Bagisto::DOWNLOADABLE_PRODUCT) {
            $data['links'] = [$downloadableLink2->id];
        }
        if ($scenario['product_type2'] === Bagisto::BOOKING_EVENT_PRODUCT) {
            $data['booking'] = ['qty' => [$bookingTicket2->id => 1]];
        }

        $I->comment('Guest is adding a product of type ' . $product2->type . ' with id ' . $product2->id . ' to cart.');
        cart()->addProduct($product2->id, $data);
        $I->assertEquals(1, cart()->getCart()->items->count());

        $I->comment('And will be logged in.');
        auth()->guard('customer')->onceUsingId($this->customer->id);

        Event::dispatch('customer.after.login', $this->customer['email']);
        $I->assertEquals(2, cart()->getCart()->items->count());

        auth()->guard('customer')->logout();
        cart()->setCart(null);
        $data = [
            '_token'     => session('_token'),
            'quantity'   => 2,
            'product_id' => $product1->id,
        ];
        if ($scenario['product_type1'] === Bagisto::DOWNLOADABLE_PRODUCT) {
            $data['links'] = [$downloadableLink1->id];
        }
        if ($scenario['product_type1'] === Bagisto::BOOKING_EVENT_PRODUCT) {
            $data['booking'] = ['qty' => [$bookingTicket1->id => 2]];
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
                'product_type1' => Bagisto::SIMPLE_PRODUCT,
                'product_type2' => Bagisto::SIMPLE_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::VIRTUAL_PRODUCT,
                'product_type2' => Bagisto::VIRTUAL_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::SIMPLE_PRODUCT,
                'product_type2' => Bagisto::VIRTUAL_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::VIRTUAL_PRODUCT,
                'product_type2' => Bagisto::SIMPLE_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::DOWNLOADABLE_PRODUCT,
                'product_type2' => Bagisto::DOWNLOADABLE_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::DOWNLOADABLE_PRODUCT,
                'product_type2' => Bagisto::SIMPLE_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::SIMPLE_PRODUCT,
                'product_type2' => Bagisto::DOWNLOADABLE_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::DOWNLOADABLE_PRODUCT,
                'product_type2' => Bagisto::VIRTUAL_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::VIRTUAL_PRODUCT,
                'product_type2' => Bagisto::DOWNLOADABLE_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::BOOKING_EVENT_PRODUCT,
                'product_type2' => Bagisto::BOOKING_EVENT_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::BOOKING_EVENT_PRODUCT,
                'product_type2' => Bagisto::SIMPLE_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::SIMPLE_PRODUCT,
                'product_type2' => Bagisto::BOOKING_EVENT_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::BOOKING_EVENT_PRODUCT,
                'product_type2' => Bagisto::VIRTUAL_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::VIRTUAL_PRODUCT,
                'product_type2' => Bagisto::BOOKING_EVENT_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::BOOKING_EVENT_PRODUCT,
                'product_type2' => Bagisto::DOWNLOADABLE_PRODUCT,
            ],
            [
                'product_type1' => Bagisto::DOWNLOADABLE_PRODUCT,
                'product_type2' => Bagisto::BOOKING_EVENT_PRODUCT,
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
        
        cart()->setCart(null);

        session()->forget('cart');
    }

    /**
     * @param int $productId
     *
     * @return int|null
     */
    private function getCartItemIdFromProduct(int $productId): ?int
    {
        foreach(cart()->getCart()->items as $item) {
            if ($item->product_id === $productId) {
                return $item->id;
            }
        }

        return null;
    }
}