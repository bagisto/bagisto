<?php

use Carbon\Carbon;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Core\Models\Visit;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;

use function Pest\Laravel\get;

afterEach(function () {
    // Cleaning up the row which are creating.
    Customer::query()->delete();
    OrderAddress::query()->delete();
    Order::query()->delete();
    OrderPayment::query()->delete();
    CartItem::query()->delete();
    Cart::query()->delete();
    Product::query()->delete();
    Invoice::query()->delete();
    Visit::query()->delete();
});

it('should return the sales index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.sales.index.title'))
        ->assertSeeText(trans('admin::app.reporting.sales.index.refunds'))
        ->assertSeeText(trans('admin::app.reporting.sales.index.total-sales'));
});

it('should returns the sales stats', function () {
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'type' => 'total-sales',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.sales.progress', 100)
        ->assertJsonPath('statistics.sales.formatted_total', core()->formatBasePrice($order->grand_total))
        ->assertJsonPath('statistics.over_time.current.30.count', 1);
});

it('should returns the purchase funnel stats', function () {
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

    visitor()->visit($customer);

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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'type' => 'purchase-funnel',
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.visitors.total', 1)
        ->assertJsonPath('statistics.carts.total', 1)
        ->assertJsonPath('statistics.orders.total', 1);
});

it('should returns the abandoned carts stats', function () {
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

    CartItem::factory()->create([
        'product_id' => $product->id,
        'cart_id'    => Cart::factory()->create([
            'customer_id' => Customer::factory()->create(),
            'created_at'  => Carbon::now()->subMonth()->toDateString(),
        ])->id,
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth()->toDateString(),
        'type'  => 'abandoned-carts',
        'end'   => Carbon::now()->addMonths(5)->toDateString(),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.carts.current', 1);
});

it('should returns the total orders stats', function () {
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'total-orders',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.orders.current', 1);
});

it('should returns the average sale stats', function () {
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'average-sales',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.sales.progress', 100)
        ->assertJsonPath('statistics.sales.formatted_total', core()->formatBasePrice($order->grand_total))
        ->assertJsonPath('statistics.over_time.current.30.count', 1);
});

it('should returns the shipping collected stats', function () {
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'shipping-collected',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.top_methods.0.title', 'Free Shipping');
});

it('should returns the tax collected stats', function () {
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
        'base_tax_amount_invoiced' => $taxInvoiced = rand(0, 50),
        'cart_id'                  => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'tax-collected',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.tax_collected.current', $taxInvoiced);
});

it('should returns the refunds stats', function () {
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
        'base_tax_amount_invoiced'  => $taxInvoiced = rand(20, 50),
        'base_grand_total_refunded' => $grantTotalRefunded = $taxInvoiced - 10,
        'cart_id'                   => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'refunds',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.refunds.current', $grantTotalRefunded);
});

it('should returns the top payment methods stats', function () {
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
        'base_tax_amount_invoiced'  => $taxInvoiced = rand(20, 50),
        'base_grand_total_refunded' => $grantTotalRefunded = $taxInvoiced - 10,
        'cart_id'                   => $cartId = CartItem::factory()->create([
            'product_id' => $product->id,
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.stats', [
        'start' => Carbon::now()->copy()->subMonth(),
        'type'  => 'top-payment-methods',
        'end'   => Carbon::now()->addMonths(5),
    ]))
        ->assertOk()
        ->assertJsonPath('statistics.0.method', 'cashondelivery')
        ->assertJsonPath('statistics.0.title', 'Cash On Delivery');
});

it('should return the view page of sales stats', function () {
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.reporting.sales.view', [
        'type' => 'total-sales',
    ]))
        ->assertOk()
        ->assertSeeText(trans('admin::app.reporting.sales.index.total-sales'));
});

it('should export the sales stats', function () {
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

    OrderAddress::factory()->create([
        'order_id'     => $order->id,
        'cart_id'      => $cartId,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    Invoice::factory()->create([
        'order_id'      => $order->id,
        'state'         => 'paid',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    $period = fake()->randomElement(['day', 'month', 'year']);

    $start = Carbon::now();

    if ($period === 'day') {
        $end = $start->copy()->addDay();
    } elseif ($period === 'month') {
        $end = $start->copy()->addMonth();
    } else {
        $end = $start->copy()->addYear();
    }

    get(route('admin.reporting.sales.export', [
        'start'  => $start->toDateString(),
        'end'    => $end->toDateString(),
        'format' => $format = fake()->randomElement(['csv', 'xls']),
        'period' => $period,
        'type'   => 'total-sales',
    ]))
        ->assertOk()
        ->assertDownload('total-sales.' . $format);
});
