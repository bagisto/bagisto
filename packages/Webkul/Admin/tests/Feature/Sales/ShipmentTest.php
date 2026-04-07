<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\Shipment;
use Webkul\Shop\Mail\Order\ShippedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Create a shippable order from a test context (needs $this for createSimpleProduct).
 */
function createShippableOrder($testContext): array
{
    $product = $testContext->createSimpleProduct();

    $customer = Customer::factory()->create();

    $order = Order::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'status' => 'processing',
        'channel_id' => core()->getCurrentChannel()->id,
        'shipping_method' => 'free_free',
        'shipping_title' => 'Free Shipping - Free Shipping',
    ]);

    OrderPayment::factory()->create([
        'order_id' => $order->id,
        'method' => 'cashondelivery',
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'type' => 'simple',
        'name' => $product->name,
        'qty_ordered' => 2,
    ]);

    OrderAddress::factory()->create([
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
    ]);

    OrderAddress::factory()->create([
        'order_id' => $order->id,
        'customer_id' => $customer->id,
        'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
    ]);

    return ['order' => $order, 'orderItem' => $orderItem, 'product' => $product];
}

// ============================================================================
// Index
// ============================================================================

it('should return the shipments index page', function () {
    $this->loginAsAdmin();

    get(route('admin.sales.shipments.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.shipments.index.title'));
});

it('should deny guest access to the shipments index page', function () {
    get(route('admin.sales.shipments.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a shipment for an order', function () {
    $data = createShippableOrder($this);

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $data['order']->id), [
        'shipment' => [
            'source' => 1,
            'carrier_title' => 'Free Shipping',
            'track_number' => fake()->uuid(),
            'items' => [
                $data['orderItem']->id => [1 => 1],
            ],
        ],
    ])
        ->assertRedirect();

    $this->assertDatabaseHas('shipments', [
        'order_id' => $data['order']->id,
    ]);
});

it('should store a shipment and send email notifications', function () {
    Mail::fake();

    CoreConfig::factory()->create([
        'code' => 'emails.general.notifications.emails.general.notifications.new_shipment',
        'value' => 1,
    ]);

    $data = createShippableOrder($this);

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $data['order']->id), [
        'shipment' => [
            'source' => 1,
            'carrier_title' => 'Free Shipping',
            'track_number' => fake()->uuid(),
            'items' => [
                $data['orderItem']->id => [1 => 1],
            ],
        ],
    ])
        ->assertRedirect();

    Mail::assertQueued(ShippedNotification::class);
});

it('should fail validation when shipment source is missing on store', function () {
    $data = createShippableOrder($this);

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $data['order']->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipment.source');
});

// ============================================================================
// View
// ============================================================================

it('should return the shipment view page', function () {
    $data = createShippableOrder($this);

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $data['order']->id), [
        'shipment' => [
            'source' => 1,
            'carrier_title' => 'Free Shipping',
            'track_number' => fake()->uuid(),
            'items' => [
                $data['orderItem']->id => [1 => 1],
            ],
        ],
    ]);

    $shipment = Shipment::where('order_id', $data['order']->id)->first();

    get(route('admin.sales.shipments.view', $shipment->id))
        ->assertOk();
});
