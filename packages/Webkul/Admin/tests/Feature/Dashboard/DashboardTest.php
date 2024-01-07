<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Core\Models\Visit;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    Customer::query()->delete();
    OrderAddress::query()->delete();
    Order::query()->delete();
    OrderPayment::query()->delete();
    CartItem::query()->delete();
    Cart::query()->delete();
    Invoice::query()->delete();
    Product::query()->delete();
    Visit::query()->delete();
    CustomerAddress::query()->delete();
});

it('should return the dashboard index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.dashboard.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.dashboard.index.title'))
        ->assertSeeText(trans('admin::app.dashboard.index.overall-details'))
        ->assertSeeText(trans('admin::app.dashboard.index.total-sales'))
        ->assertSeeText(trans('admin::app.dashboard.index.product-image'))
        ->assertSeeText(trans('admin::app.dashboard.index.today-sales'));
});

it('should show the dashboard over all stats', function () {
    // Arrange
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

    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
        ])->id,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ])->create();

    // Act and Assertd
    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', [
        'type' => 'over-all',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.total_customers.current', 1)
        ->assertJsonPath('statistics.total_orders.current', 1)
        ->assertJsonPath('statistics.total_sales.current', $order->grand_total);
});

it('should show the dashboard today stats', function () {
    // Arrange
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

    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
        ])->id,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ])->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', [
        'type' => 'today',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.total_customers.current', 1)
        ->assertJsonPath('statistics.total_orders.current', 1)
        ->assertJsonPath('statistics.total_sales.current', $order->grand_total)
        ->assertJsonPath('statistics.orders.0.id', $order->id)
        ->assertJsonPath('statistics.orders.0.status', $order->status)
        ->assertJsonPath('statistics.orders.0.customer_email', $order->customer->email);
});

it('should show the dashboard stock threshold products stats', function () {
    // Arrange
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

    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
        ])->id,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ])->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', [
        'type' => 'stock-threshold-products',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $product->id)
        ->assertJsonPath('statistics.0.sku', $product->sku);
});

it('should show the dashboard total sales stats', function () {
    // Arrange
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

    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
        ])->id,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id' => $product->id,
        'order_id'   => $order->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ])->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', [
        'type' => 'total-sales',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.total_orders.current', 1)
        ->assertJsonPath('statistics.total_sales.current', $order->grand_total);
});

it('should show the dashboard total visitors stats', function () {
    // Arrange
    (new ProductFaker([
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

    visitor()->visit();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', [
        'type' => 'total-visitors',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.total.current', 1)
        ->assertJsonPath('statistics.unique.current', 1);
});

it('should show the dashboard top selling products stats', function () {
    // Arrange
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

    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
        ])->id,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id'          => $product->id,
        'order_id'            => $order->id,
        'base_total_invoiced' => $order->base_sub_total_invoiced,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ])->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', [
        'type' => 'top-selling-products',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $product->id);
});

it('should show the dashboard top customers stats', function () {
    // Arrange
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

    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'cart_id' => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
            'sku'        => $product->sku,
            'type'       => $product->type,
            'name'       => $product->name,
        ])->id,
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    OrderItem::factory()->create([
        'product_id'          => $product->id,
        'order_id'            => $order->id,
        'base_total_invoiced' => $order->base_sub_total_invoiced,
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
    ]);

    $customerAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
    ]);

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address1'     => $customerAddress->address1,
        'country'      => $customerAddress->country,
        'state'        => $customerAddress->state,
        'city'         => $customerAddress->city,
        'postcode'     => $customerAddress->postcode,
        'phone'        => $customerAddress->phone,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ])->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.dashboard.stats', [
        'type' => 'top-customers',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.id', $order->customer->id)
        ->assertJsonPath('statistics.0.email', $order->customer->email)
        ->assertJsonPath('statistics.0.full_name', $order->customer->name)
        ->assertJsonPath('statistics.0.orders', $order->count());
});
