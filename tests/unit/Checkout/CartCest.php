<?php

namespace Tests\Unit\Checkout;

use UnitTester;
use Illuminate\Support\Facades\Event;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductEventTicket;
use Webkul\Customer\Models\Customer;
use Webkul\Core\Helpers\Laravel5Helper;

class CartCest
{
    private $customer;
    private $simple1, $simple2;
    private $virtual1, $virtual2;
    private $downloadable1, $downloadable2;
    private $downloadableLinkId1, $downloadableLinkId2;
    private $booking1, $booking2;
    private $bookingTicket1, $bookingTicket2;

    /**
     * @param \UnitTester          $I
     * @param \Codeception\Example $scenario
     *
     * @throws \Exception
     */
    public function testMergeCart(UnitTester $I): void
    {
        $this->createProducts($I);

        $scenarios = $this->getMergeCartScenarios();

        foreach ($scenarios as $scenario) {
            $I->comment("Check, I'm a guest");
            $I->assertFalse(auth()->guard('customer')->check());

            $data = [
                '_token'     => session('_token'),
                'quantity'   => 1,
                'product_id' => $scenario['products'][0]['product']->id,
            ];
            $data = array_merge($data, $scenario['products'][0]['data']);

            $I->comment('A guest is adding a first product to cart');
            cart()->addProduct($scenario['products'][0]['product']->id, $data);
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
                'product_id' => $scenario['products'][1]['product']->id,
            ];
            $data = array_merge($data, $scenario['products'][1]['data']);

            $I->comment('Again, guest is adding a ' . $scenario['product_type'] . ' product to cart.');
            cart()->addProduct($scenario['products'][1]['product']->id, $data);
            $I->assertEquals(1, cart()->getCart()->items->count());

            $I->comment('And will be logged in.');
            auth()->guard('customer')->onceUsingId($this->customer->id);

            Event::dispatch('customer.after.login', $this->customer['email']);
            $I->assertEquals($scenario['results']['cart_items_count'], cart()->getCart()->items->count());

            auth()->guard('customer')->logout();
            $data = [
                '_token'     => session('_token'),
                'quantity'   => 2,
                'product_id' => $scenario['products'][0]['product']->id,
            ];
            $data = array_merge($data, $scenario['products'][0]['data']);

            $I->comment('Again, guest is adding first ' . $scenario['product_type'] . ' product again.');
            cart()->addProduct($scenario['products'][0]['product']->id, $data);
            $I->assertEquals(1, cart()->getCart()->items->count());
            $I->assertEquals(2, cart()->getCart()->items_qty);

            $I->comment('And will be logged in.');
            auth()->guard('customer')->onceUsingId($this->customer->id);

            Event::dispatch('customer.after.login', $this->customer['email']);
            $I->assertEquals($scenario['results']['cart_items_count'], cart()->getCart()->items->count());
            $I->assertEquals($scenario['results']['cart_items_quantity'], cart()->getCart()->items_qty);

            $this->cleanUp();
            $I->comment('===  ' . $scenario['product_type'] . ' DONE  ===');
        }
    }

    private function getMergeCartScenarios(): array
    {
        return [
            [
                'product_type' => 'simple',
                'products'     => [
                    [
                        'product' => $this->simple1,
                        'data'    => [],
                    ],
                    [
                        'product' => $this->simple2,
                        'data'    => [],
                    ],
                ],
                'results'      => [
                    'cart_items_count'    => 2,
                    'cart_items_quantity' => 4,
                ],
            ],
            [
                'product_type' => 'virtual',
                'products'     => [
                    [
                        'product' => $this->virtual1,
                        'data'    => [],
                    ],
                    [
                        'product' => $this->virtual2,
                        'data'    => [],
                    ],
                ],
                'results'      => [
                    'cart_items_count'    => 2,
                    'cart_items_quantity' => 4,
                ],
            ],
            [
                'product_type' => 'downloadable',
                'products'     => [
                    [
                        'product' => $this->downloadable1,
                        'data'    => [
                            'links' => [$this->downloadableLinkId1],
                        ],
                    ],
                    [
                        'product' => $this->downloadable2,
                        'data'    => [
                            'links' => [$this->downloadableLinkId2],
                        ],
                    ],
                ],
                'results'      => [
                    'cart_items_count'    => 2,
                    'cart_items_quantity' => 4,
                ],
            ],
            [
                'product_type' => 'booking',
                'products'     => [
                    [
                        'product' => $this->booking1,
                        'data'    => [
                            'booking' => [
                                'qty' => [
                                    $this->bookingTicket1->id => 1,
                                ],
                            ],
                        ],
                    ],
                    [
                        'product' => $this->booking2,
                        'data'    => [
                            'booking'   => [
                                'qty' => [
                                    $this->bookingTicket2->id => 1,
                                ],
                            ],
                        ],
                    ],
                ],
                'results'      => [
                    'cart_items_count'    => 2,
                    'cart_items_quantity' => 4,
                ],
            ],
        ];
    }

    private function createProducts(UnitTester $I)
    {
        $this->customer = $I->have(Customer::class);

        $this->simple1 = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, []);
        $this->simple2 = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, []);

        $this->virtual1 = $I->haveProduct(Laravel5Helper::VIRTUAL_PRODUCT, []);
        $this->virtual2 = $I->haveProduct(Laravel5Helper::VIRTUAL_PRODUCT, []);

        $this->downloadable1 = $I->haveProduct(Laravel5Helper::DOWNLOADABLE_PRODUCT, []);
        $this->downloadableLinkId1 = $I->grabRecord(
            'product_downloadable_links',
            [
                'product_id' => $this->downloadable1->id,
            ]
        )['id'];

        $this->downloadable2 = $I->haveProduct(Laravel5Helper::DOWNLOADABLE_PRODUCT, []);
        $this->downloadableLinkId2 = $I->grabRecord(
            'product_downloadable_links',
            [
                'product_id' => $this->downloadable2->id,
            ]
        )['id'];

        $this->booking1 = $I->haveProduct(Laravel5Helper::BOOKING_EVENT_PRODUCT, []);
        $bookingProduct1 = BookingProduct::query()->where('product_id', $this->booking1->id)->firstOrFail();
        $this->bookingTicket1 = BookingProductEventTicket::query()->where('booking_product_id', $bookingProduct1->id)->firstOrFail();

        $this->booking2 = $I->haveProduct(Laravel5Helper::BOOKING_EVENT_PRODUCT, []);
        $bookingProduct2 = BookingProduct::query()->where('product_id', $this->booking2->id)->firstOrFail();
        $this->bookingTicket2 = BookingProductEventTicket::query()->where('booking_product_id', $bookingProduct2->id)->firstOrFail();
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