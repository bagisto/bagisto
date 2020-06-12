<?php

use Carbon\Carbon;
use Codeception\Example;
use Webkul\BookingProduct\Helpers\EventTicket;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductEventTicket;
use Webkul\Checkout\Models\CartItem;
use Webkul\Core\Helpers\Laravel5Helper;
use Webkul\Product\Models\Product;

class BookingProductEventTicketCest
{
    protected $typeHelper, $bookingProduct;

    public function _before(UnitTester $I): void
    {
        $this->typeHelper = app(EventTicket::class);

        $product = $I->haveProduct(Laravel5Helper::VIRTUAL_PRODUCT);
        Product::query()->where('id', $product->id)->update(['type' => 'booking']);

        $availableTo = Carbon::now()->addMinutes($I->fake()->numberBetween(2, 59));

        $this->bookingProduct = $I->have(BookingProduct::class, [
            'type'         => 'event',
            'available_to' => $availableTo->toDateTimeString(),
            'product_id'   => $product->id,
        ]);
    }

    /**
     * @param UnitTester $I
     * @param Example    $scenario
     *
     * @dataProvider getTestDataForFormatPrice
     */
    public function testFormatPrice(UnitTester $I, Example $scenario): void
    {
        $tickets[] = $I->have(BookingProductEventTicket::class, array_merge(
                ['booking_product_id' => $this->bookingProduct->id], $scenario['ticket'])
        );

        $formattedTickets = $this->typeHelper->formatPrice($tickets);

        foreach ($scenario['expectFields'] as $field) {
            $I->assertEquals($scenario['expectFields']['converted_price'], $formattedTickets[0]['converted_price']);
        }

    }

    /**
     * @param UnitTester $I
     * @param Example    $scenario
     *
     * @dataProvider getTestDataForAddAdditionalPrices
     */
    public function testAddAdditionalPrices(UnitTester $I, Example $scenario): void
    {
        $ticket = $I->have(BookingProductEventTicket::class, array_merge(
                ['booking_product_id' => $this->bookingProduct->id], $scenario['ticket'])
        );

        $inputData = $scenario['inputData'];
        $inputData['product_id'] = $this->bookingProduct->product_id;
        $inputData['additional']['product_id'] = $this->bookingProduct->product_id;
        $inputData['additional']['booking']['ticket_id'] = $ticket->id;

        $addTicketPrices = $this->typeHelper->addAdditionalPrices([$inputData]);

        $I->assertEquals($scenario['expected']['price'], $addTicketPrices[0]['price']);
        $I->assertEquals($scenario['expected']['base_price'], $addTicketPrices[0]['base_price']);
        $I->assertEquals($scenario['expected']['total'], $addTicketPrices[0]['total']);
        $I->assertEquals($scenario['expected']['base_total'], $addTicketPrices[0]['base_total']);
    }

    /**
     * @param UnitTester $I
     * @param Example    $scenario
     *
     * @dataProvider getTestDataForValidateCartItem
     */
    public function testValidateCartItem(UnitTester $I, Example $scenario): void
    {
        $ticket = $I->have(BookingProductEventTicket::class, array_merge(
                ['booking_product_id' => $this->bookingProduct->id], $scenario['ticket'])
        );

        $product = Product::query()->find($this->bookingProduct->product_id);

        $data = [
            'is_buy_now' => 0,
            'product_id' => $product->id,
            'quantity'   => $scenario['qty'],
            "booking"    => [
                "qty" => [
                    $ticket->id => $scenario['qty'],
                ]
            ]
        ];

        $cart = cart()->addProduct($product->id, $data);
        $I->assertEquals('booking', $cart->items[0]->type);

        $product->getTypeInstance()->validateCartItem($cart->items[0]);

        $finalPrice = $product->price + $scenario['expected'];
        $finalTotal = ($product->price + $scenario['expected']) * $scenario['qty'];

        $I->seeRecord(CartItem::class, [
            'id'         => $cart->items[0]->id,
            'price'      => core()->convertPrice($finalPrice),
            'base_price' => $finalPrice,
            'total'      => core()->convertPrice($finalTotal),
            'base_total' => $finalTotal,
        ]);
    }

    /**
     * @param UnitTester $I
     * @param Example    $scenario
     *
     * @dataProvider getTestDataForHasSalePrice
     */
    public function testHasSalePrice(UnitTester $I, Example $scenario): void
    {
        $ticket = $I->have(BookingProductEventTicket::class, array_merge(
                ['booking_product_id' => $this->bookingProduct->id], $scenario['ticket'])
        );

        $I->assertEquals($scenario['expect'], $this->typeHelper->isInSale($ticket));
    }

    /* Data Providers */

    private function getTestDataForFormatPrice(): array
    {
        return [
            [
                'ticket'       => ['price' => 10],
                'expectFields' => [
                    'converted_price'     => 10,
                    'formated_price'      => '$10.00',
                    'formated_price_text' => '$10.00 Per Ticket'
                ]
            ],
            [
                'ticket'       => ['price' => 20, 'special_price' => 10],
                'expectFields' => [
                    'converted_price'          => 10,
                    'formated_price'           => '$10.00',
                    'formated_price_text'      => '$10.00 Per Ticket',
                    'original_converted_price' => 20,
                    'original_formated_price'  => '$20.00',
                ]
            ],
            [
                'ticket'       => [
                    'price'              => 20,
                    'special_price'      => 10,
                    'special_price_from' => '0000-00-00 00:00:00',
                    'special_price_to'   => '0000-00-00 00:00:00',
                ],
                'expectFields' => [
                    'converted_price'          => 10,
                    'formated_price'           => '$10.00',
                    'formated_price_text'      => '$10.00 Per Ticket',
                    'original_converted_price' => 20,
                    'original_formated_price'  => '$20.00',
                ]
            ],
            [
                'ticket'       => [
                    'price'              => 10,
                    'special_price'      => 7,
                    'special_price_from' => Carbon::yesterday(),
                    'special_price_to'   => Carbon::now(),
                ],
                'expectFields' => [
                    'converted_price'     => 10,
                    'formated_price'      => '$10.00',
                    'formated_price_text' => '$10.00 Per Ticket',
                ]
            ],
        ];
    }

    private function getTestDataForAddAdditionalPrices(): array
    {
        return [
            [
                'ticket'    => ['price' => 5],
                'inputData' => [
                    'quantity'   => 1,
                    'price'      => 10.0,
                    'base_price' => 10.0,
                    'total'      => 10.0,
                    'base_total' => 10.0,
                    'additional' => [
                        'quantity' => 1,
                    ]
                ],
                'expected'  => [
                    'price'      => 15.0,
                    'base_price' => 15.0,
                    'total'      => 15.0,
                    'base_total' => 15.0,
                ]
            ],
            [
                'ticket'    => ['price' => 20, 'special_price' => 10],
                'inputData' => [
                    'quantity'   => 1,
                    'price'      => 20.0,
                    'base_price' => 20.0,
                    'total'      => 20.0,
                    'base_total' => 20.0,
                    'additional' => [
                        'quantity' => 1,
                    ]
                ],
                'expected'  => [
                    'price'      => 30.0,
                    'base_price' => 30.0,
                    'total'      => 30.0,
                    'base_total' => 30.0,
                ]
            ],
            [
                'ticket'    => ['price' => 20, 'special_price' => 10],
                'inputData' => [
                    'quantity'   => 2,
                    'price'      => 20.0,
                    'base_price' => 20.0,
                    'total'      => 20.0,
                    'base_total' => 20.0,
                    'additional' => [
                        'quantity' => 2,
                    ]
                ],
                'expected'  => [
                    'price'      => 30.0,
                    'base_price' => 30.0,
                    'total'      => 40.0,
                    'base_total' => 40.0,
                ]
            ],
        ];
    }

    private function getTestDataForValidateCartItem(): array
    {
        return [
            [
                'ticket'   => ['price' => 10],
                'qty'      => 1,
                'expected' => 10,
            ],
            [
                'ticket'   => ['price' => 20, 'special_price' => 10],
                'qty'      => 1,
                'expected' =>  10,
            ],
            [
                'ticket'   => ['price' => 20, 'special_price' => 10],
                'qty'      => 2,
                'expected' => 10
            ],
            [
                'ticket'       => [
                    'price'              => 20,
                    'special_price'      => 10,
                    'special_price_from' => '0000-00-00 00:00:00',
                    'special_price_to'   => '0000-00-00 00:00:00',
                ],
                'qty'      => 2,
                'expected' => 10
            ],
            [
                'ticket'       => [
                    'price'              => 10,
                    'special_price'      => 7,
                    'special_price_from' => Carbon::yesterday(),
                    'special_price_to'   => Carbon::now(),
                ],
                'qty'      => 2,
                'expected' => 10
            ],
        ];
    }

    private function getTestDataForHasSalePrice(): array
    {
        return [
            [
                'ticket' => [
                    'price'         => '10.0000',
                    'special_price' => null
                ],
                'expect' => false
            ],
            [
                'ticket' => [
                    'price'         => '10.0000',
                    'special_price' => '5.0000'
                ],
                'expect' => true
            ],
            [
                'ticket' => [
                    'price'              => '10.0000',
                    'special_price'      => '5.0000',
                    'special_price_from' => null,
                    'special_price_to'   => null,
                ],
                'expect' => true
            ],
            [
                'ticket' => [
                    'price'              => '10.0000',
                    'special_price'      => '5.0000',
                    'special_price_from' => '0000-00-00 00:00:00',
                    'special_price_to'   => '0000-00-00 00:00:00',
                ],
                'expect' => true
            ],
            [
                'ticket' => [
                    'price'              => '10.0000',
                    'special_price'      => '5.0000',
                    'special_price_from' => Carbon::yesterday(),
                    'special_price_to'   => Carbon::tomorrow(),
                ],
                'expect' => true
            ],
            [
                'ticket' => [
                    'price'              => '10.0000',
                    'special_price'      => '5.0000',
                    'special_price_from' => Carbon::yesterday(),
                    'special_price_to'   => Carbon::now(),
                ],
                'expect' => false
            ],
            [
                'ticket' => [
                    'price'              => '10.0000',
                    'special_price'      => '5.0000',
                    'special_price_from' => Carbon::now(),
                    'special_price_to'   => Carbon::tomorrow(),
                ],
                'expect' => true
            ],
        ];
    }


}
