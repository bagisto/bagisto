<?php

namespace Tests\Unit\CartRule;

use UnitTester;
use Codeception\Example;
use Faker\Factory;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Helper\Bagisto;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Illuminate\Contracts\Support\Arrayable;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;
use Cart;

class cartRuleWithCoupon
{
    public $cartRule;

    public $coupon;

    public function __construct(CartRule $cartRule, CartRuleCoupon $coupon)
    {
        $this->cartRule = $cartRule;
        $this->coupon = $coupon;
    }
}

class expectedCartItem implements Arrayable
{
    public const ITEM_DISCOUNT_AMOUNT_PRECISION = 4;

    public const ITEM_TAX_AMOUNT_PRECISION = 4;

    public $cart_id;

    public $product_id;

    public $quantity = 1;

    public $price = 0.0;

    public $base_price = 0.0;

    public $total = 0.0;

    public $base_total = 0.0;

    public $tax_percent = 0.0;

    public $tax_amount = 0.0;

    public $base_tax_amount = 0.0;

    public $coupon_code = null;

    public $discount_percent = 0.0;

    public $discount_amount = 0.0;

    public $base_discount_amount = 0.0;

    public $applied_cart_rule_ids = '';

    public function __construct(int $cartId, int $productId)
    {
        $this->cart_id = $cartId;
        $this->product_id = $productId;
    }

    public function calcTotals(): void
    {
        $this->total = $this->quantity * $this->price;
        $this->base_total = $this->quantity * $this->price;
    }

    public function calcTaxAmounts(): void
    {
        $this->tax_amount = round($this->quantity * $this->price * $this->tax_percent / 100, self::ITEM_TAX_AMOUNT_PRECISION);
        $this->base_tax_amount = round($this->quantity * $this->price * $this->tax_percent / 100, self::ITEM_TAX_AMOUNT_PRECISION);
    }

    public function calcFixedDiscountAmounts(float $discount, float $baseDiscount, string $code, int $cartRuleId): void
    {
        $this->discount_amount = $this->quantity * $discount;
        $this->base_discount_amount = $this->quantity * $baseDiscount;
        $this->coupon_code = $code;
        $this->applied_cart_rule_ids = (string)$cartRuleId;
    }

    public function calcPercentageDiscountAmounts(float $discount, string $code, int $cartRuleId): void
    {
        $this->discount_percent = $discount;
        $this->discount_amount = round(($this->total + $this->tax_amount) * $this->discount_percent / 100, self::ITEM_DISCOUNT_AMOUNT_PRECISION);
        $this->base_discount_amount = round(($this->base_total + $this->base_tax_amount) * $this->discount_percent / 100, self::ITEM_DISCOUNT_AMOUNT_PRECISION);
        $this->coupon_code = $code;
        $this->applied_cart_rule_ids = (string)$cartRuleId;
    }

    public function toArray(): array
    {
        return [
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'base_price' => $this->base_price,
            'total' => $this->total,
            'base_total' => $this->base_total,
            'tax_percent' => $this->tax_percent,
            'tax_amount' => $this->tax_amount,
            'base_tax_amount' => $this->base_tax_amount,
            'coupon_code' => $this->coupon_code,
            'discount_percent' => $this->discount_percent,
            'discount_amount' => $this->discount_amount,
            'base_discount_amount' => $this->base_discount_amount,
            'applied_cart_rule_ids' => $this->applied_cart_rule_ids,
        ];
    }
}

class expectedCart
{
    public const CART_TOTAL_PRECISION = 2;

    public $customer_id;

    public $id;

    public $items_count = 0;

    public $items_qty = 0.0;

    public $sub_total = 0.0;

    public $tax_total = 0.0;

    public $discount_amount = 0.0;

    public $grand_total = 0.0;

    public $base_sub_total = 0.0;

    public $base_tax_total = 0.0;

    public $base_discount_amount = 0.0;

    public $base_grand_total = 0.0;

    public $coupon_code = null;

    public $applied_cart_rule_ids = '';

    public function __construct(int $cartId, int $customerId)
    {
        $this->id = $cartId;
        $this->customer_id = $customerId;
    }

    public function applyCoupon(int $cartRuleId, string $couponCode): void
    {
        $this->coupon_code = $couponCode;
        $this->applied_cart_rule_ids = (string)$cartRuleId;
    }

    public function finalizeTotals(): void
    {
        $this->sub_total = round($this->sub_total, self::CART_TOTAL_PRECISION);
        $this->tax_total = round($this->tax_total, self::CART_TOTAL_PRECISION);
        $this->discount_amount = round($this->discount_amount, self::CART_TOTAL_PRECISION);
        $this->grand_total = round($this->sub_total + $this->tax_total - $this->discount_amount, self::CART_TOTAL_PRECISION);

        $this->base_sub_total = round($this->base_sub_total, self::CART_TOTAL_PRECISION);
        $this->base_tax_total = round($this->base_tax_total, self::CART_TOTAL_PRECISION);
        $this->base_discount_amount = round($this->base_discount_amount, self::CART_TOTAL_PRECISION);
        $this->base_grand_total = round($this->base_sub_total + $this->base_tax_total - $this->base_discount_amount, self::CART_TOTAL_PRECISION);
    }

    public function toArray(): array
    {
        return (array)$this;
    }
}

class expectedOrder implements Arrayable
{
    public $status;

    public $customer_email;

    public $customer_first_name;

    public $customer_vat_id;

    public $coupon_code;

    public $total_item_count;

    public $total_qty_ordered;

    public $grand_total;

    public $base_grand_total;

    public $sub_total;

    public $base_sub_total;

    public $discount_amount;

    public $base_discount_amount;

    public $tax_amount;

    public $base_tax_amount;

    public $customer_id;

    public $cart_id;

    public $applied_cart_rule_ids;

    public $shipping_method;

    public $shipping_amount;

    public $base_shipping_amount;

    public $shipping_discount_amount;

    public function __construct(expectedCart $expectedCart, Customer $customer, int $cartId)
    {
        $this->status = 'pending';
        $this->customer_email = $customer->email;
        $this->customer_first_name = $customer->first_name;
        $this->customer_vat_id = $customer->vat_id;
        $this->coupon_code = $expectedCart->coupon_code;
        $this->total_item_count = $expectedCart->items_count;
        $this->total_qty_ordered = $expectedCart->items_qty;
        $this->grand_total = $expectedCart->grand_total;
        $this->base_grand_total = $expectedCart->base_grand_total;
        $this->sub_total = $expectedCart->sub_total;
        $this->base_sub_total = $expectedCart->base_sub_total;
        $this->discount_amount = $expectedCart->discount_amount;
        $this->base_discount_amount = $expectedCart->base_discount_amount;
        $this->tax_amount = $expectedCart->tax_total;
        $this->base_tax_amount = $expectedCart->base_tax_total;
        $this->customer_id = $customer->id;
        $this->cart_id = $cartId;
        $this->applied_cart_rule_ids = $expectedCart->applied_cart_rule_ids;
        $this->shipping_method = null;
        $this->shipping_amount = null;
        $this->base_shipping_amount = null;
        $this->shipping_discount_amount = null;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'customer_email' => $this->customer_email,
            'customer_first_name' => $this->customer_first_name,
            'customer_vat_id' => $this->customer_vat_id,
            'coupon_code' => $this->coupon_code,
            'total_item_count' => $this->total_item_count,
            'total_qty_ordered' => $this->total_qty_ordered,
            'grand_total' => $this->grand_total,
            'base_grand_total' => $this->base_grand_total,
            'sub_total' => $this->sub_total,
            'base_sub_total' => $this->base_sub_total,
            'discount_amount' => $this->discount_amount,
            'base_discount_amount' => $this->base_discount_amount,
            'tax_amount' => $this->tax_amount,
            'base_tax_amount' => $this->base_tax_amount,
            'customer_id' => $this->customer_id,
            'cart_id' => $this->cart_id,
            'applied_cart_rule_ids' => $this->applied_cart_rule_ids,
            'shipping_method' => $this->shipping_method,
            'shipping_amount' => $this->shipping_amount,
            'base_shipping_amount' => $this->base_shipping_amount,
            'shipping_discount_amount' => $this->shipping_discount_amount,
        ];
    }
}

class CartRuleCest
{
    private $products;

    private $sessionToken;

    public const PRODUCT_PRICE = 13.57;

    public const REDUCED_PRODUCT_PRICE = 7.21;

    public const TAX_RATE = 18.5;

    public const REDUCED_TAX_RATE = 5.5;

    public const DISCOUNT_AMOUNT_FIX = 3.37;

    public const DISCOUNT_AMOUNT_PERCENT = 7.5;

    public const DISCOUNT_AMOUNT_FIX_FULL = 999999.99;

    public const DISCOUNT_AMOUNT_CART = 8.33;

    public const ACTION_TYPE_FIXED = "by_fixed";

    public const ACTION_TYPE_PERCENTAGE = "by_percent";

    public const ACTION_TYPE_CART_FIXED = "cart_fixed";

    public const PRODUCT_FREE = 0;

    public const PRODUCT_NOT_FREE = 1;

    public const PRODUCT_NOT_FREE_REDUCED_TAX = 2;

    public const TAX_CATEGORY = 0;

    public const TAX_REDUCED_CATEGORY = 1;

    public const COUPON_FIXED = 0;

    public const COUPON_FIXED_FULL = 1;

    public const COUPON_PERCENTAGE = 2;

    public const COUPON_PERCENTAGE_FULL = 3;

    public const COUPON_FIXED_CART = 4;


    protected function getCartWithCouponScenarios(): array
    {
        return [
            // [
            //     'name'            => 'check cart coupon',
            //     'productSequence' => [
            //         self::PRODUCT_NOT_FREE,
            //         self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         self::PRODUCT_NOT_FREE,
            //     ],
            //     'withCoupon'      => true,
            //     'couponScenario'  => [
            //         'scenario' => self::COUPON_FIXED_CART,
            //         'products' => [
            //         ],
            //     ],
            //     'checkOrder'      => true,
            // ],
            //  ohne coupon
            [
                'name' => 'PRODUCT_FREE no coupon',
                'productSequence' => [
                    self::PRODUCT_FREE,
                ],
                'withCoupon' => false,
                'checkOrder' => false,
            ],
            [
                'name' => 'PRODUCT_NOT_FREE no coupon',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => false,
                'checkOrder' => false,
            ],
            // fixer Coupon für ein Produkt (Warenkorb wird nicht 0)
            [
                'name' => 'PRODUCT_NOT_FREE fix coupon',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => true,
                'couponScenario' => [
                    'scenario' => self::COUPON_FIXED,
                    'products' => [
                        self::PRODUCT_NOT_FREE,
                    ],
                ],
                'checkOrder' => false,
            ],
            [
                'name' => 'check fix coupon on product with quantity=2',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                    self::PRODUCT_NOT_FREE_REDUCED_TAX,
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => true,
                'couponScenario' => [
                    'scenario' => self::COUPON_FIXED,
                    'products' => [
                        self::PRODUCT_NOT_FREE,
                    ],
                ],
                'checkOrder' => false,
            ],
            // [
            //     'name'            => 'check fix coupon applied to two products',
            //     'productSequence' => [
            //         self::PRODUCT_NOT_FREE,
            //         self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         self::PRODUCT_NOT_FREE,
            //     ],
            //     'withCoupon'      => true,
            //     'couponScenario'  => [
            //         'scenario' => self::COUPON_FIXED,
            //         'products' => [
            //             self::PRODUCT_NOT_FREE,
            //             self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         ],
            //     ],
            //     'checkOrder'      => true,
            // ],
            // prozenturaler Coupon für ein Produkt (Warenkorb wird nicht 0)
            [
                'name' => 'PRODUCT_NOT_FREE percentage coupon',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => true,
                'couponScenario' => [
                    'scenario' => self::COUPON_PERCENTAGE,
                    'products' => [
                        self::PRODUCT_NOT_FREE,
                    ],
                ],
                'checkOrder' => false,
            ],
            [
                'name' => 'check percentage coupon on product with quantity=2',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                    self::PRODUCT_NOT_FREE_REDUCED_TAX,
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => true,
                'couponScenario' => [
                    'scenario' => self::COUPON_PERCENTAGE,
                    'products' => [
                        self::PRODUCT_NOT_FREE,
                    ],
                ],
                'checkOrder' => false,
            ],
            // [
            //     'name'            => 'check percentage coupon applied to two products',
            //     'productSequence' => [
            //         self::PRODUCT_NOT_FREE,
            //         self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         self::PRODUCT_NOT_FREE,
            //     ],
            //     'withCoupon'      => true,
            //     'couponScenario'  => [
            //         'scenario' => self::COUPON_PERCENTAGE,
            //         'products' => [
            //             self::PRODUCT_NOT_FREE,
            //             self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         ],
            //     ],
            //     'checkOrder'      => true,
            // ],
            // fixer Coupon für ein Produkt (Warenkorb wird 0)
            [
                'name' => 'PRODUCT_NON_SUB_NOT_FREE fix coupon to zero',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => true,
                'couponScenario' => [
                    'scenario' => self::COUPON_FIXED_FULL,
                    'products' => [
                        self::PRODUCT_NOT_FREE,
                    ],
                ],
                'checkOrder' => false,
            ],
            [
                'name' => 'check fix coupon to zero on product with quantity=2',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                    self::PRODUCT_NOT_FREE_REDUCED_TAX,
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => true,
                'couponScenario' => [
                    'scenario' => self::COUPON_FIXED_FULL,
                    'products' => [
                        self::PRODUCT_NOT_FREE,
                    ],
                ],
                'checkOrder' => false,
            ],
            // [
            //     'name'            => 'check fix coupon to zero applied to two products',
            //     'productSequence' => [
            //         self::PRODUCT_NOT_FREE,
            //         self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         self::PRODUCT_NOT_FREE,
            //     ],
            //     'withCoupon'      => true,
            //     'couponScenario'  => [
            //         'scenario' => self::COUPON_FIXED_FULL,
            //         'products' => [
            //             self::PRODUCT_NOT_FREE,
            //             self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         ],
            //     ],
            //     'checkOrder'      => true,
            // ],
            // prozenturaler Coupon für ein Produkt (Warenkorb wird 0)
            [
                'name' => 'PRODUCT_NOT_FREE percentage coupon to zero',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => true,
                'couponScenario' => [
                    'scenario' => self::COUPON_PERCENTAGE_FULL,
                    'products' => [
                        self::PRODUCT_NOT_FREE,
                    ],
                ],
                'checkOrder' => false,
            ],
            [
                'name' => 'check percentage coupon to zero on product with quantity=2',
                'productSequence' => [
                    self::PRODUCT_NOT_FREE,
                    self::PRODUCT_NOT_FREE_REDUCED_TAX,
                    self::PRODUCT_NOT_FREE,
                ],
                'withCoupon' => true,
                'couponScenario' => [
                    'scenario' => self::COUPON_PERCENTAGE_FULL,
                    'products' => [
                        self::PRODUCT_NOT_FREE,
                    ],
                ],
                'checkOrder' => false,
            ],
            // [
            //     'name'            => 'check percentage coupon to zero applied to two products',
            //     'productSequence' => [
            //         self::PRODUCT_NOT_FREE,
            //         self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         self::PRODUCT_NOT_FREE,
            //     ],
            //     'withCoupon'      => true,
            //     'couponScenario'  => [
            //         'scenario' => self::COUPON_PERCENTAGE_FULL,
            //         'products' => [
            //             self::PRODUCT_NOT_FREE,
            //             self::PRODUCT_NOT_FREE_REDUCED_TAX,
            //         ],
            //     ],
            //     'checkOrder'      => true,
            // ],
        ];
    }

    /**
     * @param  \UnitTester  $I
     * @param  \Codeception\Example  $scenario
     *
     * @dataProvider getCartWithCouponScenarios
     * @group        slow_unit
     * @throws \Exception
     */
    public function checkCartWithCoupon(UnitTester $I, Example $scenario): void
    {
        $faker = Factory::create();

        config(['app.default_country' => 'DE']);

        $customer = $I->have(Customer::class);
        auth()
            ->guard('customer')
            ->loginUsingId($customer->id);
        Event::dispatch('customer.after.login', $customer['email']);

        $this->sessionToken = $faker->uuid;
        session(['_token' => $this->sessionToken]);

        $taxCategories = $this->generateTaxCategories($I);
        $this->products = $this->generateProducts($I, $scenario['productSequence'], $taxCategories);

        $cartRuleWithCoupon = null;
        if ($scenario['withCoupon']) {
            $cartRuleWithCoupon = $this->generateCartRuleWithCoupon($I, $scenario['couponScenario']);
        }

        foreach ($scenario['productSequence'] as $productIndex) {
            $data = [
                '_token' => session('_token'),
                'product_id' => $this->products[$productIndex]->id,
                'quantity' => 1,
            ];

            cart()->addProduct($this->products[$productIndex]->id, $data);
        }

        if ($scenario['withCoupon']) {
            $expectedCartCoupon = $cartRuleWithCoupon->coupon->code;
            $I->comment('I try to use coupon code ' . $expectedCartCoupon);
            cart()
                ->setCouponCode($expectedCartCoupon)
                ->collectTotals();
        } else {
            $I->comment('I have no coupon');
            $expectedCartCoupon = null;
        }

        $cart = cart()->getCart();
        $I->assertEquals($expectedCartCoupon, $cart->coupon_code);

        $expectedCartItems = $this->getExpectedCartItems($scenario, $cartRuleWithCoupon, $cart->id);
        $expectedCartItems = $this->checkMaxDiscount($expectedCartItems);

        foreach ($expectedCartItems as $expectedCartItem) {
            /**
             * @var $expectedCartItem \Tests\Unit\CartRule\expectedCartItem
             */

            $I->seeRecord('cart_items', $expectedCartItem->toArray());
        }

        $expectedCart = $this->getExpectedCart($cart->id, $expectedCartItems, $cartRuleWithCoupon);
        $I->seeRecord(\Webkul\Checkout\Models\Cart::class, $expectedCart->toArray());

        if ($scenario['checkOrder']) {
            $I->wantTo('create and check order from cart');

            $customerAddress = $I->have(CustomerAddress::class, [
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'country' => 'DE',
            ]);

            $billing = [
                'address1' => $customerAddress->address1,
                'use_for_shipping' => true,
                'first_name' => $customerAddress->first_name,
                'last_name' => $customerAddress->last_name,
                'email' => $customer->email,
                'company_name' => $customerAddress->company_name,
                'city' => $customerAddress->city,
                'postcode' => $customerAddress->postcode,
                'country' => $customerAddress->country,
                'state' => $customerAddress->state,
                'phone' => $customerAddress->phone,
            ];

            $shipping = [
                'address1' => '',
                'first_name' => $customerAddress->first_name,
                'last_name' => $customerAddress->last_name,
                'email' => $customer->email,
            ];

            cart()->saveCustomerAddress([
                'billing' => $billing,
                'shipping' => $shipping,
            ]);

            cart()->saveShippingMethod('free_free');
            cart()->savePaymentMethod(['method' => 'mollie_creditcard']);
            $I->assertFalse(cart()->hasError());
            $orderItemRepository = new OrderItemRepository(app());
            $downloadableLinkRepository = new ProductDownloadableLinkRepository(app());
            $downloadableLinkPurchasedRepository = new DownloadableLinkPurchasedRepository($downloadableLinkRepository, app());
            $orderRepository = new OrderRepository($orderItemRepository, $downloadableLinkPurchasedRepository, app());

            $orderRepository->create(cart()->prepareDataForOrder());
            $expectedOrder = new expectedOrder($expectedCart, $customer, $cart->id);
            $I->seeRecord('orders', $expectedOrder->toArray());

            auth()
                ->guard('customer')
                ->logout();
        }
    }

    public function checkExampleCase(UnitTester $I)
    {
        config(['app.default_country' => 'DE']);

        $faker = Factory::create();

        $customer = $I->have(Customer::class);

        auth()
            ->guard('customer')
            ->loginUsingId($customer->id);
        Event::dispatch('customer.after.login', $customer['email']);

        $this->sessionToken = $faker->uuid;
        session(['_token' => $this->sessionToken]);

        $tax = $I->have(TaxRate::class, [
            'country' => 'DE',
            'tax_rate' => 19.0,
        ]);

        $taxCategorie = $I->have(TaxCategory::class);

        $I->have(TaxMap::class, [
            'tax_rate_id' => $tax->id,
            'tax_category_id' => $taxCategorie->id,
        ]);

        $productConfig = [
            'attributeValues' => [
                'price' => 23.92,
                'tax_category_id' => $taxCategorie->id,
            ],
        ];
        $product = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, $productConfig);

        $ruleConfig = [
            'action_type' => self::ACTION_TYPE_PERCENTAGE,
            'discount_amount' => 100,
            'conditions' => [
                [
                    'attribute' => 'product|sku',
                    'value' => $product->sku,
                    'operator' => '==',
                    'attribute_type' => 'text',
                ],
            ],
        ];
        $cartRule = $I->have(CartRule::class, $ruleConfig);

        DB::table('cart_rule_channels')
          ->insert([
              'cart_rule_id' => $cartRule->id,
              'channel_id' => core()->getCurrentChannel()->id,
          ]);

        $guestCustomerGroup = $I->grabRecord('customer_groups', ['code' => 'guest']);
        DB::table('cart_rule_customer_groups')
          ->insert([
              'cart_rule_id' => $cartRule->id,
              'customer_group_id' => $guestCustomerGroup['id'],
          ]);

        $generalCustomerGroup = $I->grabRecord('customer_groups', ['code' => 'general']);
        DB::table('cart_rule_customer_groups')
          ->insert([
              'cart_rule_id' => $cartRule->id,
              'customer_group_id' => $generalCustomerGroup['id'],
          ]);

        $coupon = $I->have(CartRuleCoupon::class, [
            'code' => 'AWESOME',
            'cart_rule_id' => $cartRule->id,
        ]);


        $data = [
            '_token' => session('_token'),
            'product_id' => $product->id,
            'quantity' => 1,
        ];
        cart()->addProduct($product->id, $data);
        cart()
            ->setCouponCode('AWESOME')
            ->collectTotals();

        $cart = cart()->getCart();
        $cartItem = $cart->items()
                         ->first();

        $I->assertEquals('AWESOME', $cartItem['coupon_code']);
        $I->assertEquals(23.92, $cartItem['price']);
        $I->assertEquals(19.0, $cartItem['tax_percent']);
        $I->assertEquals(4.5448, $cartItem['tax_amount']);
        $I->assertEquals(28.4648, $cartItem['discount_amount']);

        $I->assertEquals('AWESOME', $cart->coupon_code);
        $I->assertEquals(23.92, $cart->sub_total);
        $I->assertEquals(4.54, $cart->tax_total);
        $I->assertEquals(28.46, $cart->discount_amount);
        // 23.92 + 4.54 - 28.46 = 0.00
        $I->assertEquals(0.00, $cart->grand_total);
    }

    /**
     * @param  \Codeception\Example  $scenario
     * @param  \Tests\Unit\Category\cartRuleWithCoupon  $cartRuleWithCoupon
     * @param  int  $cartID
     *
     * @return array
     */
    private function getExpectedCartItems(Example $scenario, ?cartRuleWithCoupon $cartRuleWithCoupon, int $cartID): array
    {
        $cartItems = [];

        foreach ($scenario['productSequence'] as $key => $item) {
            $pos = $this->array_find('product_id', $this->products[$scenario['productSequence'][$key]]->id, $cartItems);

            if ($pos === null) {
                $cartItem = new expectedCartItem($cartID, $this->products[$scenario['productSequence'][$key]]->id);
            } else {
                $cartItem = $cartItems[$pos];
                $cartItem->quantity++;
            }

            switch ($item) {
                case self::PRODUCT_FREE:
                    $cartItem->tax_percent = self::TAX_RATE;
                    break;

                case self::PRODUCT_NOT_FREE:
                    $cartItem->price = self::PRODUCT_PRICE;
                    $cartItem->base_price = self::PRODUCT_PRICE;
                    $cartItem->tax_percent = self::TAX_RATE;

                    $cartItem->calcTotals();
                    $cartItem->calcTaxAmounts();
                    break;

                case self::PRODUCT_NOT_FREE_REDUCED_TAX:
                    $cartItem->price = self::REDUCED_PRODUCT_PRICE;
                    $cartItem->base_price = self::REDUCED_PRODUCT_PRICE;
                    $cartItem->tax_percent = self::REDUCED_TAX_RATE;

                    $cartItem->calcTotals();
                    $cartItem->calcTaxAmounts();
                    break;
            }

            if ($scenario['withCoupon']) {
                switch ($scenario['couponScenario']['scenario']) {
                    case self::COUPON_FIXED:
                        foreach ($scenario['couponScenario']['products'] as $couponItem) {
                            if ($item === $couponItem) {
                                $cartItem->calcFixedDiscountAmounts(self::DISCOUNT_AMOUNT_FIX, self::DISCOUNT_AMOUNT_FIX, $cartRuleWithCoupon->coupon->code, $cartRuleWithCoupon->cartRule->id);
                                continue;
                            }
                        }
                        break;

                    case self::COUPON_FIXED_FULL:
                        foreach ($scenario['couponScenario']['products'] as $couponItem) {
                            if ($item === $couponItem) {
                                $cartItem->calcFixedDiscountAmounts(self::DISCOUNT_AMOUNT_FIX_FULL, self::DISCOUNT_AMOUNT_FIX_FULL, $cartRuleWithCoupon->coupon->code, $cartRuleWithCoupon->cartRule->id);
                                continue;
                            }
                        }
                        break;

                    case self::COUPON_PERCENTAGE:
                        foreach ($scenario['couponScenario']['products'] as $couponItem) {
                            if ($item === $couponItem) {
                                $cartItem->calcPercentageDiscountAmounts(self::DISCOUNT_AMOUNT_PERCENT, $cartRuleWithCoupon->coupon->code, $cartRuleWithCoupon->cartRule->id);
                                continue;
                            }
                        }
                        break;

                    case self::COUPON_PERCENTAGE_FULL:
                        foreach ($scenario['couponScenario']['products'] as $couponItem) {
                            if ($item === $couponItem) {
                                $cartItem->calcPercentageDiscountAmounts(100.0, $cartRuleWithCoupon->coupon->code, $cartRuleWithCoupon->cartRule->id);
                                continue;
                            }
                        }
                        break;
                }
            }

            if ($pos === null) {
                $cartItems[] = $cartItem;
            } else {
                $cartItems[$pos] = $cartItem;
            }
        }

        if ($scenario['withCoupon'] && $scenario['couponScenario']['scenario'] === self::COUPON_FIXED_CART) {
            $totals = $this->calcTotals($cartItems);
            $cartItems = $this->splitDiscountToItems($cartItems, $cartRuleWithCoupon, $totals);
        }

        return $cartItems;
    }

    private function calcTotals(array $cartItems): array
    {
        $result = [
            'subTotal' => 0.0,
            'baseSubTotal' => 0.0,
        ];
        foreach ($cartItems as $expectedCartItem) {
            $result['subTotal'] += $expectedCartItem->total;
            $result['baseSubTotal'] += $expectedCartItem->base_total;
        }
        $result['subTotal'] = round($result['subTotal'], expectedCart::CART_TOTAL_PRECISION);
        $result['baseSubTotal'] = round($result['baseSubTotal'], expectedCart::CART_TOTAL_PRECISION);

        return $result;
    }

    private function splitDiscountToItems(
        array $cartItems,
        cartRuleWithCoupon $cartRuleWithCoupon,
        array $totals
    ): array {
        $discountAmount = self::DISCOUNT_AMOUNT_CART;
        $baseDiscountAmount = self::DISCOUNT_AMOUNT_CART;
        // split coupon amount to cart items
        $length = count($cartItems) - 1;
        for ($i = 0; $i < $length; $i++) {
            $cartItems[$i]->discount_amount = round(self::DISCOUNT_AMOUNT_CART * $cartItems[$i]->total / $totals['subTotal'], expectedCartItem::ITEM_DISCOUNT_AMOUNT_PRECISION);
            $discountAmount -= $cartItems[$i]->discount_amount;

            $cartItems[$i]->base_discount_amount = round(self::DISCOUNT_AMOUNT_CART * $cartItems[$i]->base_total / $totals['baseSubTotal'], expectedCartItem::ITEM_DISCOUNT_AMOUNT_PRECISION);
            $baseDiscountAmount -= $cartItems[$i]->discount_amount;

            $cartItems[$i]->coupon_code = $cartRuleWithCoupon->coupon->code;
            $cartItems[$i]->applied_cart_rule_ids = (string)$cartRuleWithCoupon->cartRule->id;
        }

        $cartItems[$length]->discount_amount = $discountAmount;
        $cartItems[$length]->base_discount_amount = $baseDiscountAmount;

        $cartItems[$length]->coupon_code = $cartRuleWithCoupon->coupon->code;
        $cartItems[$length]->applied_cart_rule_ids = (string)$cartRuleWithCoupon->cartRule->id;

        return $cartItems;
    }

    /**
     * @param  array  $expectedCartItems
     *
     * @return array
     */
    private function checkMaxDiscount(array $expectedCartItems): array
    {
        foreach ($expectedCartItems as $key => $cartItem) {
            $itemGrandTotal = round($cartItem->total + $cartItem->tax_amount, expectedCartItem::ITEM_DISCOUNT_AMOUNT_PRECISION);
            if ($cartItem->discount_amount > $itemGrandTotal) {
                $expectedCartItems[$key]->discount_amount = $itemGrandTotal;
            }

            $itemBaseGrandTotal = round($cartItem->base_total + $cartItem->base_tax_amount, expectedCartItem::ITEM_DISCOUNT_AMOUNT_PRECISION);
            if ($cartItem->base_discount_amount > $itemBaseGrandTotal) {
                $expectedCartItems[$key]->base_discount_amount = $itemBaseGrandTotal;
            }
        }

        return $expectedCartItems;
    }

    /**
     * @param  int  $cartId
     * @param  array  $expectedCartItems
     *
     * @param  \Tests\Unit\Category\cartRuleWithCoupon  $cartRuleWithCoupon
     *
     * @return \Tests\Unit\Category\expectedCart
     */
    private function getExpectedCart(int $cartId, array $expectedCartItems, ?cartRuleWithCoupon $cartRuleWithCoupon): expectedCart
    {
        $cart = new expectedCart($cartId, auth()
            ->guard('customer')
            ->user()->id);

        if ($cartRuleWithCoupon) {
            $cart->applyCoupon($cartRuleWithCoupon->cartRule->id, $cartRuleWithCoupon->coupon->code);
        }

        foreach ($expectedCartItems as $cartItem) {
            $cart->items_count++;
            $cart->items_qty += $cartItem->quantity;

            $cart->sub_total += $cartItem->total;
            $cart->tax_total += $cartItem->tax_amount;
            $cart->discount_amount += $cartItem->discount_amount;

            $cart->base_sub_total += $cartItem->base_total;
            $cart->base_tax_total += $cartItem->base_tax_amount;
            $cart->base_discount_amount += $cartItem->base_discount_amount;
        }

        $cart->finalizeTotals();

        return $cart;
    }

    /**
     * @param  \UnitTester  $I
     *
     * @return array
     */
    private function generateTaxCategories(UnitTester $I): array
    {
        $result = [];
        $country = strtoupper(Config::get('app.default_country')) ?? 'DE';
        foreach ($this->getTaxRateSpecifications() as $taxSpec => $taxRate) {
            $tax = $I->have(TaxRate::class, [
                'country' => $country,
                'tax_rate' => $taxRate,
            ]);

            $taxCategorie = $I->have(TaxCategory::class);

            $I->have(TaxMap::class, [
                'tax_rate_id' => $tax->id,
                'tax_category_id' => $taxCategorie->id,
            ]);

            $result[$taxSpec] = $taxCategorie->id;
        }

        return $result;
    }

    /**
     * @param  \UnitTester  $I
     * @param  array  $scenario
     * @param  array  $taxCategories
     *
     * @return array
     * @throws \Exception
     */
    private function generateProducts(UnitTester $I, array $scenario, array $taxCategories): array
    {
        $products = [];
        $productSpecs = $this->getProductSpecifications();

        foreach ($scenario as $item) {
            $productConfig = $this->makeProductConfig($productSpecs[$item], $taxCategories);
            $products[$item] = $I->haveProduct($productSpecs[$item]['productType'], $productConfig);
        }

        return $products;
    }

    /**
     * @param  \UnitTester  $I
     * @param  array  $couponConfig
     *
     * @return \Tests\Unit\Category\cartRuleWithCoupon
     */
    private function generateCartRuleWithCoupon(UnitTester $I, array $couponConfig): cartRuleWithCoupon
    {
        $faker = Factory::create();

        $couponSpecifications = $this->getCouponSpecifications();
        $ruleConfig = $this->makeRuleConfig($couponSpecifications[$couponConfig['scenario']], $this->products, $couponConfig['products']);
        $cartRule = $I->have(CartRule::class, $ruleConfig);

        DB::table('cart_rule_channels')
          ->insert([
              'cart_rule_id' => $cartRule->id,
              'channel_id' => core()->getCurrentChannel()->id,
          ]);

        $guestCustomerGroup = $I->grabRecord('customer_groups', ['code' => 'guest']);
        DB::table('cart_rule_customer_groups')
          ->insert([
              'cart_rule_id' => $cartRule->id,
              'customer_group_id' => $guestCustomerGroup['id'],
          ]);

        $generalCustomerGroup = $I->grabRecord('customer_groups', ['code' => 'general']);
        DB::table('cart_rule_customer_groups')
          ->insert([
              'cart_rule_id' => $cartRule->id,
              'customer_group_id' => $generalCustomerGroup['id'],
          ]);

        $coupon = $I->have(CartRuleCoupon::class, [
            'cart_rule_id' => $cartRule->id,
        ]);

        return new cartRuleWithCoupon($cartRule, $coupon);
    }

    /**
     * @param  array  $productSpec
     * @param  array  $taxCategories
     *
     * @return array
     */
    private function makeProductConfig(array $productSpec, array $taxCategories): array
    {
        $result = [
            'productInventory' => [
                'qty' => 100,
            ],
            'attributeValues' => [
                'price' => 0.0,
                'tax_category_id' => $taxCategories[self::TAX_CATEGORY],
            ],
        ];

        if ($productSpec['reducedTax']) {
            if (array_key_exists(self::TAX_REDUCED_CATEGORY, $taxCategories)) {
                $result['attributeValues']['tax_category_id'] = $taxCategories[self::TAX_REDUCED_CATEGORY];
            }
        }

        if (!$productSpec['freeOfCharge']) {
            if ($productSpec['reducedTax']) {
                $result['attributeValues']['price'] = self::REDUCED_PRODUCT_PRICE;
            } else {
                $result['attributeValues']['price'] = self::PRODUCT_PRICE;
            }
        }

        return $result;
    }

    /**
     * @param  array  $ruleSpec
     * @param  array  $products
     * @param  array  $couponableProducts
     *
     * @return array
     */
    private function makeRuleConfig(array $ruleSpec, array $products, array $couponableProducts): array
    {
        foreach ($couponableProducts as $item) {
            $conditions[] = [
                'value' => $products[$item]->sku,
                'operator' => '==',
                'attribute' => 'product|sku',
                'attribute_type' => 'text',
            ];
        }

        $result = [
            'action_type' => $ruleSpec['actionType'],
            'discount_amount' => $ruleSpec['discountAmount'],
            'conditions' => $conditions ?? null,
        ];

        return $result;
    }

    /**
     * @return array
     */
    private function getProductSpecifications(): array
    {
        return [
            [
                'productScenario' => self::PRODUCT_FREE,
                'productType' => Bagisto::SIMPLE_PRODUCT,
                'freeOfCharge' => true,
                'reducedTax' => false,
            ],
            [
                'productScenario' => self::PRODUCT_NOT_FREE,
                'productType' => Bagisto::SIMPLE_PRODUCT,
                'freeOfCharge' => false,
                'reducedTax' => false,
            ],
            [
                'productScenario' => self::PRODUCT_NOT_FREE_REDUCED_TAX,
                'productType' => Bagisto::SIMPLE_PRODUCT,
                'freeOfCharge' => false,
                'reducedTax' => true,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getCouponSpecifications(): array
    {
        return [
            [
                'couponScenario' => self::COUPON_FIXED,
                'actionType' => self::ACTION_TYPE_FIXED,
                'discountAmount' => self::DISCOUNT_AMOUNT_FIX,
            ],
            [
                'couponScenario' => self::COUPON_FIXED_FULL,
                'actionType' => self::ACTION_TYPE_FIXED,
                'discountAmount' => self::DISCOUNT_AMOUNT_FIX_FULL,
            ],
            [
                'couponScenario' => self::COUPON_PERCENTAGE,
                'actionType' => self::ACTION_TYPE_PERCENTAGE,
                'discountAmount' => self::DISCOUNT_AMOUNT_PERCENT,
            ],
            [
                'couponScenario' => self::COUPON_PERCENTAGE_FULL,
                'actionType' => self::ACTION_TYPE_PERCENTAGE,
                'discountAmount' => 100.0,
            ],
            [
                'couponScenario' => self::COUPON_FIXED_CART,
                'actionType' => self::ACTION_TYPE_CART_FIXED,
                'discountAmount' => self::DISCOUNT_AMOUNT_CART,
            ],
        ];
    }

    /**
     * @return array
     */
    private function getTaxRateSpecifications(): array
    {
        return [
            self::TAX_CATEGORY => self::TAX_RATE,
            self::TAX_REDUCED_CATEGORY => self::REDUCED_TAX_RATE,
        ];
    }

    /**
     * @param  string  $param
     * @param        $needleValue
     * @param  array  $data
     *
     * @return int|null
     */
    private function array_find(string $param, $needleValue, array $data): ?int
    {
        foreach ($data as $pos => $object) {
            if ($object->$param === $needleValue) {
                return $pos;
            }
        }

        return null;
    }
}