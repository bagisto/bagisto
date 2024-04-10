<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Faker\Helpers\Customer as CustomerFaker;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should search the customers via email or name', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->count(5)->create()->first();

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.search'), [
        'query' => fake()->randomElement([$customer->name, $customer->email]),
    ])
        ->assertOk()
        ->assertJsonPath('data.0.id', $customer->id)
        ->assertJsonPath('data.0.first_name', $customer->first_name)
        ->assertJsonPath('data.0.email', $customer->email);
});

it('should create the customer if none exists when creating an order', function () {
    // Arrange.
    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.sales.orders.create', $cart->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.create.title', ['name' => $cart->customer->name]))
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.items.add-product'))
        ->assertSeeText(trans('admin::app.sales.orders.create.wishlist-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.compare-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.recent-order-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.items.title'));
});

it('should search the products via name for adding products to the cart', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->count(5)
        ->create()
        ->first();

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.search'), [
        'query' => $product->name,
    ])
        ->assertOk()
        ->assertSeeText($product->id)
        ->assertSeeText($product->name)
        ->assertSeeText($product->type)
        ->assertSeeText($product->sku);
});

it('should add product to the cart after search the product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    $response = postJson(route('admin.sales.cart.items.store', $cart->id), $data = [
        'product_id' => $product->id,
        'quantity'   => rand(1, 10),
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.is_guest', $cart->is_guest)
        ->assertJsonPath('data.customer_id', $cart->customer_id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', $data['quantity'])
        ->assertJsonPath('data.base_sub_total', core()->currency($price = $product->price * $data['quantity']))
        ->assertJsonPath('data.base_tax_total', 0)
        ->assertJsonPath('data.formatted_base_discount_amount', core()->currency(0))
        ->assertJsonPath('data.base_discount_amount', 0)
        ->assertJsonPath('data.base_grand_total', core()->currency($price))
        ->assertJsonPath('data.selected_shipping_rate', core()->currency(0))
        ->assertJsonPath('data.coupon_code', null)
        ->assertJsonPath('data.selected_shipping_rate_method', '')
        ->assertJsonPath('data.formatted_grand_total', core()->formatPrice($price))
        ->assertJsonPath('data.formatted_sub_total', core()->formatPrice($price))
        ->assertJsonPath('data.tax_total', 0)
        ->assertJsonPath('data.formatted_tax_total', core()->formatPrice(0))
        ->assertJsonPath('data.discount_amount', 0)
        ->assertJsonPath('data.formatted_discount_amount', core()->formatPrice(0))
        ->assertJsonPath('data.items.0.product.id', $data['product_id'])
        ->assertJsonPath('data.items.0.product.name', $product->name)
        ->assertJsonPath('data.items.0.product.type', $product->type)
        ->assertJsonPath('data.items.0.product.sku', $product->sku)
        ->assertJsonPath('data.billing_address', null)
        ->assertJsonPath('data.shipping_address', null)
        ->assertJsonPath('data.have_stockable_items', true)
        ->assertJsonPath('data.payment_method', null)
        ->assertJsonPath('data.payment_method_title', '');

    $this->assertPrice($price, $response->json('data.grand_total'));

    $this->assertPrice($price, $response->json('data.sub_total'));

    $this->assertModelWise([
        CartItem::class => [
            [
                'cart_id'    => $cart->id,
                'product_id' => $data['product_id'],
                'sku'        => $product->sku,
                'type'       => $product->type,
                'name'       => $product->name,
                'price'      => $product->price,
                'total'      => $price,
            ],
        ],
    ]);
});

it('should update the cart item after add product to the cart', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
        ],

        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
        ],
    ]))
        ->getSimpleProductFactory()
        ->create();

    $customer = (new CustomerFaker())->factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
    ]);

    $additional = [
        'product_id' => $product->id,
        'rating'     => '0',
        'is_buy_now' => '0',
        'quantity'   => '1',
    ];

    $cartItem = CartItem::factory()->create([
        'cart_id'           => $cart->id,
        'product_id'        => $product->id,
        'sku'               => $product->sku,
        'quantity'          => $additional['quantity'],
        'name'              => $product->name,
        'price'             => $convertedPrice = core()->convertPrice($product->price),
        'base_price'        => $product->price,
        'total'             => $convertedPrice * $additional['quantity'],
        'base_total'        => ($product->price) * $additional['quantity'],
        'weight'            => $product->weight ?? 0,
        'total_weight'      => ($product->weight ?? 0) * $additional['quantity'],
        'base_total_weight' => ($product->weight ?? 0) * $additional['quantity'],
        'type'              => $product->type,
        'additional'        => $additional,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.sales.cart.items.update', $cart->id), [
        'qty' => [
            $cartItem->id => $qty = rand(2, 10),
        ],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.success-update'));

    $cart->refresh();

    $cartItem->refresh();

    $this->assertPrice($product->price * $qty, $cart->grand_total);

    $this->assertPrice($product->price * $qty, $cart->sub_total);

    $this->assertModelWise([
        Cart::class => [
            [
                'customer_id' => $customer->id,
                'items_count' => 1,
                'items_qty'   => number_format($qty, 4),
            ],
        ],

        CartItem::class => [
            [
                'id'         => $cartItem->id,
                'cart_id'    => $cart->id,
                'product_id' => $product->id,
                'sku'        => $product->sku,
                'type'       => $product->type,
                'name'       => $product->name,
                'price'      => $product->price,
                'total'      => $product->price * $qty,
            ],
        ],
    ]);
});
