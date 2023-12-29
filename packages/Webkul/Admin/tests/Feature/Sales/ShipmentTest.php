<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\Shipment;
use Webkul\Sales\Models\ShipmentItem;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

afterEach(function () {
    // Cleaning up the row  which are creating
    Customer::query()->delete();
    OrderAddress::query()->delete();
    Order::query()->delete();
    OrderPayment::query()->delete();
    CartItem::query()->delete();
    Cart::query()->delete();
    Product::query()->delete();
    Shipment::query()->delete();
    ShipmentItem::query()->delete();
    CustomerAddress::query()->delete();
});

it('should returns the shipment page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.sales.shipments.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.shipments.index.title'));
});

it('should store the shimpment to the order', function () {
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
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    $shipmentSources = $order->channel->inventory_sources->pluck('id')->toArray();

    foreach ($order->items as $item) {
        foreach ($order->channel->inventory_sources as $inventorySource) {
            $items[$item->id][$inventorySource->id] = $inventorySource->id;
        }
    }

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $order->id), [
        'shipment' => [
            'source'        => fake()->randomElement($shipmentSources),
            'items'         => $items,
            'carrier_title' => $carrierTitle = fake()->name(),
            'track_number'  => $trackNumber = rand(111111, 222222),
        ],
    ])
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->isRedirection();

    $this->assertDatabaseHas('shipments', [
        'order_id'      => $order->id,
        'carrier_title' => $carrierTitle,
        'track_number'  => $trackNumber,
    ]);
});

it('should return the view page of shipments', function () {
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

    $address = OrderAddress::factory()->create([
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

    $shipment = Shipment::factory()->create([
        'total_qty'             => rand(1, 10),
        'total_weight'          => rand(1, 10),
        'carrier_code'          => rand(111111, 222222),
        'carrier_title'         => fake()->word(),
        'track_number'          => rand(111111, 222222),
        'customer_id'           => Customer::factory(),
        'customer_type'         => Customer::class,
        'order_id'              => $order->id,
        'order_address_id'      => $address->id,
        'inventory_source_id'   => 1,
        'inventory_source_name' => 'Default',
    ]);

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.sales.shipments.view', $shipment->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.shipments.view.title', ['shipment_id' => $shipment->id]))
        ->assertSeeText(trans('admin::app.account.edit.back-btn'));
});
