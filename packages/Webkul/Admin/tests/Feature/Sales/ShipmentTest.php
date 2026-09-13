<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\Shipment;
use Webkul\Shop\Mail\Order\ShippedNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

/**
 * Create a simple product holding the given stock in the default inventory source.
 */
function productInStock(int $qty): Product
{
    $product = test()->createSimpleProduct();

    $product->inventories()->update(['qty' => $qty]);

    return $product->fresh();
}

/**
 * The shipment payload shipping the given quantity of an order item from the default source.
 */
function shipmentPayload(int $orderItemId, int $qty, ?int $sourceId = null): array
{
    $sourceId ??= test()->defaultInventorySourceId();

    return [
        'shipment' => [
            'source' => $sourceId,
            'carrier_title' => 'Free Shipping',
            'track_number' => fake()->uuid(),
            'items' => [$orderItemId => [$sourceId => $qty]],
        ],
    ];
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

it('should ship the ordered quantity, record it on the item and take it from the inventory source', function () {
    $product = productInStock(10);

    $order = $this->createOrder(items: [['product' => $product, 'qty_ordered' => 2]]);

    ProductOrderedInventory::query()->create([
        'product_id' => $product->id,
        'channel_id' => $order->channel_id,
        'qty' => 2,
    ]);

    $item = $order->items->first();

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $order->id), shipmentPayload($item->id, 2))
        ->assertRedirect(route('admin.sales.orders.view', $order->id))
        ->assertSessionHas('success', trans('admin::app.sales.shipments.create.success'));

    $shipment = Shipment::query()->where('order_id', $order->id)->firstOrFail();

    expect($shipment->total_qty)->toBe(2)
        ->and($shipment->inventory_source_id)->toBe($this->defaultInventorySourceId());

    $this->assertDatabaseHas('shipment_items', [
        'shipment_id' => $shipment->id,
        'order_item_id' => $item->id,
        'qty' => 2,
    ]);

    $this->assertDatabaseHas('order_items', [
        'id' => $item->id,
        'qty_shipped' => 2,
    ]);

    $this->assertDatabaseHas('product_inventories', [
        'product_id' => $product->id,
        'inventory_source_id' => $this->defaultInventorySourceId(),
        'qty' => 8,
    ]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'product_id' => $product->id,
        'channel_id' => $order->channel_id,
        'qty' => 0,
    ]);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_PROCESSING,
    ]);
});

it('should complete the order once it is both invoiced and shipped', function () {
    $order = $this->createOrder(items: [['product' => productInStock(10), 'qty_ordered' => 2]]);

    $this->invoiceOrder($order);

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $order->id), shipmentPayload($order->items->first()->id, 2))
        ->assertRedirect(route('admin.sales.orders.view', $order->id));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_COMPLETED,
    ]);
});

it('should hold the order in pending payment when it ships against an unpaid invoice', function () {
    $order = $this->createOrder(items: [['product' => productInStock(10), 'qty_ordered' => 1]]);

    $this->invoiceOrder($order, state: Invoice::STATUS_PENDING);

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $order->id), shipmentPayload($order->items->first()->id, 1))
        ->assertRedirect(route('admin.sales.orders.view', $order->id));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_PENDING_PAYMENT,
    ]);
});

it('should refuse to ship more than the quantity left to ship', function () {
    $order = $this->createOrder(items: [['product' => productInStock(10), 'qty_ordered' => 2]]);

    $this->loginAsAdmin();

    post(route('admin.sales.shipments.store', $order->id), shipmentPayload($order->items->first()->id, 3))
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.shipments.create.quantity-invalid'));

    $this->assertDatabaseMissing('shipments', ['order_id' => $order->id]);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'qty_shipped' => 0,
    ]);
});

it('should refuse to ship more than the inventory source holds', function () {
    $order = $this->createOrder(items: [['product' => productInStock(1), 'qty_ordered' => 2]]);

    $this->loginAsAdmin();

    post(route('admin.sales.shipments.store', $order->id), shipmentPayload($order->items->first()->id, 2))
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.shipments.create.quantity-invalid'));

    $this->assertDatabaseMissing('shipments', ['order_id' => $order->id]);
});

it('should refuse to ship an order with nothing left to ship', function () {
    $order = $this->createOrder(items: [['product' => productInStock(10), 'qty_ordered' => 1]]);

    $this->shipOrder($order);

    $this->loginAsAdmin();

    post(route('admin.sales.shipments.store', $order->id), shipmentPayload($order->items->first()->id, 1))
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.shipments.create.order-error'));

    expect(Shipment::query()->where('order_id', $order->id)->count())->toBe(1);
});

it('should refuse to ship an order made of non-stockable items', function () {
    $order = $this->createOrder(items: [['product' => $this->createVirtualProduct()]]);

    $this->loginAsAdmin();

    post(route('admin.sales.shipments.store', $order->id), shipmentPayload($order->items->first()->id, 1))
        ->assertRedirect()
        ->assertSessionHas('error', trans('admin::app.sales.shipments.create.order-error'));

    $this->assertDatabaseMissing('shipments', ['order_id' => $order->id]);
});

it('should store a shipment and send email notifications', function () {
    Mail::fake();

    $this->setConfig('emails.general.notifications.emails.general.notifications.new_shipment', 1);

    $order = $this->createOrder(items: [['product' => productInStock(10), 'qty_ordered' => 2]]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $order->id), shipmentPayload($order->items->first()->id, 1))
        ->assertRedirect();

    $this->assertDatabaseHas('shipments', ['order_id' => $order->id]);

    Mail::assertQueued(ShippedNotification::class, fn (ShippedNotification $mail) => $mail->hasTo($order->customer_email));
});

it('should fail validation when shipment source is missing on store', function () {
    $order = $this->createOrder(items: [['product' => productInStock(10)]]);

    $this->loginAsAdmin();

    postJson(route('admin.sales.shipments.store', $order->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipment.source');
});

// ============================================================================
// View
// ============================================================================

it('should return the shipment view page', function () {
    $order = $this->createOrder(items: [['product' => productInStock(10), 'qty_ordered' => 2]]);

    $shipment = $this->shipOrder($order);

    $this->loginAsAdmin();

    get(route('admin.sales.shipments.view', $shipment->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.shipments.view.title', ['shipment_id' => $shipment->id]))
        ->assertSeeText($shipment->track_number);
});
