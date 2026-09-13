<?php

namespace Webkul\Sales\Tests\Concerns;

use Illuminate\Support\Arr;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\Shipment;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\ShipmentRepository;

trait OrderTestBench
{
    /**
     * The unit price an order item carries when neither the item nor its product names one.
     */
    protected static float $defaultOrderItemPrice = 100;

    /**
     * Create an order for a customer with a payment, both addresses and one item per entry in `$items`.
     *
     * An item entry may carry a `product` to attach a real product; `payment_method` is taken from the attributes.
     */
    public function createOrder(array $attributes = [], array $items = [[]], ?CustomerContract $customer = null): Order
    {
        $customer ??= Customer::factory()->create();

        return $this->buildOrder(array_merge([
            'is_guest' => 0,
            'customer_id' => $customer->id,
            'customer_email' => $customer->email,
            'customer_first_name' => $customer->first_name,
            'customer_last_name' => $customer->last_name,
        ], $attributes), $items);
    }

    /**
     * Create a guest order with a payment, both addresses and one item per entry in `$items`.
     */
    public function createGuestOrder(array $attributes = [], array $items = [[]]): Order
    {
        return $this->buildOrder(array_merge([
            'is_guest' => 1,
            'customer_id' => null,
            'customer_email' => fake()->safeEmail(),
            'customer_first_name' => fake()->firstName(),
            'customer_last_name' => fake()->lastName(),
        ], $attributes), $items);
    }

    /**
     * Add an item to an order, taking sku, name, type and price from the `product` entry when one is given.
     */
    public function createOrderItem(Order $order, array $attributes = []): OrderItem
    {
        $product = Arr::pull($attributes, 'product');

        $qty = $attributes['qty_ordered'] ?? 1;

        $price = $attributes['price'] ?? $product?->price ?? static::$defaultOrderItemPrice;

        $weight = $product?->weight ?? 0;

        return OrderItem::factory()->create(array_merge([
            'order_id' => $order->id,
            'product_id' => $product?->id,
            'sku' => $product?->sku ?? fake()->uuid(),
            'type' => $product?->type ?? 'simple',
            'name' => $product?->name ?? fake()->words(3, true),
            'qty_ordered' => $qty,
            'price' => $price,
            'base_price' => $price,
            'price_incl_tax' => $price,
            'base_price_incl_tax' => $price,
            'total' => $price * $qty,
            'base_total' => $price * $qty,
            'total_incl_tax' => $price * $qty,
            'base_total_incl_tax' => $price * $qty,
            'weight' => $weight,
            'total_weight' => $weight * $qty,
            'additional' => [
                'product_id' => $product?->id,
                'quantity' => $qty,
            ],
        ], $attributes));
    }

    /**
     * Invoice an order through the invoice pipeline, for the given item quantities or everything still open.
     */
    public function invoiceOrder(Order $order, array $itemQuantities = [], ?string $state = null, ?string $orderState = null): Invoice
    {
        if (! $itemQuantities) {
            $itemQuantities = $order->items()
                ->get()
                ->mapWithKeys(fn (OrderItem $item) => [$item->id => $item->qty_to_invoice])
                ->all();
        }

        return app(InvoiceRepository::class)->create([
            'order_id' => $order->id,
            'invoice' => [
                'items' => $itemQuantities,
            ],
        ], $state, $orderState);
    }

    /**
     * Ship an order through the shipment pipeline, for the given item quantities or everything still unshipped.
     */
    public function shipOrder(Order $order, array $itemQuantities = [], ?int $inventorySourceId = null): Shipment
    {
        $inventorySourceId ??= $this->defaultInventorySourceId();

        if (! $itemQuantities) {
            $itemQuantities = $order->items()
                ->get()
                ->mapWithKeys(fn (OrderItem $item) => [$item->id => $item->qty_to_ship])
                ->all();
        }

        return app(ShipmentRepository::class)->create([
            'order_id' => $order->id,
            'shipment' => [
                'source' => $inventorySourceId,
                'carrier_title' => 'Free Shipping',
                'track_number' => fake()->uuid(),
                'items' => collect($itemQuantities)
                    ->map(fn (int $qty) => [$inventorySourceId => $qty])
                    ->all(),
            ],
        ]);
    }

    /**
     * The inventory source the default channel ships from.
     */
    public function defaultInventorySourceId(): int
    {
        return core()->getDefaultChannel()->inventory_sources()->orderBy('id')->firstOrFail()->id;
    }

    /**
     * Persist an order, its payment, its items and its addresses, deriving the totals from the items.
     */
    protected function buildOrder(array $attributes, array $items): Order
    {
        $paymentMethod = Arr::pull($attributes, 'payment_method', 'cashondelivery');

        $order = Order::factory()->create(array_merge([
            'status' => Order::STATUS_PENDING,
            'channel_id' => core()->getDefaultChannel()->id,
            'sub_total_invoiced' => 0,
            'base_sub_total_invoiced' => 0,
            'grand_total_invoiced' => 0,
            'base_grand_total_invoiced' => 0,
            'sub_total_refunded' => 0,
            'base_sub_total_refunded' => 0,
            'grand_total_refunded' => 0,
            'base_grand_total_refunded' => 0,
        ], $attributes));

        OrderPayment::factory()->create([
            'order_id' => $order->id,
            'method' => $paymentMethod,
        ]);

        foreach ($items as $item) {
            $this->createOrderItem($order, $item);
        }

        OrderAddress::factory()->create([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'email' => $order->customer_email,
            'address_type' => OrderAddress::ADDRESS_TYPE_BILLING,
        ]);

        OrderAddress::factory()->create([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'email' => $order->customer_email,
            'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING,
        ]);

        $this->deriveOrderTotals($order, $attributes);

        return $order->refresh()->load('items');
    }

    /**
     * Fill in the totals the caller left out, so that they add up to the order's items.
     */
    protected function deriveOrderTotals(Order $order, array $attributes): void
    {
        $items = $order->items()->get();

        $subTotal = $items->sum('base_total');

        $order->forceFill(array_diff_key([
            'total_item_count' => $items->count(),
            'total_qty_ordered' => $items->sum('qty_ordered'),
            'sub_total' => $subTotal,
            'base_sub_total' => $subTotal,
            'sub_total_incl_tax' => $subTotal,
            'base_sub_total_incl_tax' => $subTotal,
            'grand_total' => $subTotal,
            'base_grand_total' => $subTotal,
        ], $attributes))->save();
    }
}
