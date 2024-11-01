<?php

declare(strict_types=1);

namespace Frosko\FairySync\Jobs;

use Frosko\FairySync\Repositories\OrderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Webkul\Sales\Models\Order;

class SyncOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(OrderRepository $orderRepository, \Webkul\Sales\Repositories\OrderRepository $appOrderRepository)
    {
        /** @var Order $order */
        $order = $appOrderRepository->find(1);
        $order->load('customer', 'items');

        $orderData = $orderRepository->prepareOrderData(
            order_id: data_get($order, 'id'),
            first_name: data_get($order, 'customer_first_name'),
            last_name: data_get($order, 'customer_last_name'),
            email: data_get($order, 'customer_email'),
            telephone: data_get($order, 'shipping_address.phone', ''),
            city: data_get($order, 'shipping_address.city'),
            postal_code: data_get($order, 'shipping_address.postcode'),
            country_code: data_get($order, 'shipping_address.country'),
            order_date: $order->created_at->toDateTimeString(),
            pay_type: 1,
            pay_method: 1,
            sub_total: data_get($order, 'sub_total'),
            total: data_get($order, 'grand_total'),
            shipping_amount: data_get($order, 'shipping_amount'),
            discounts: data_get($order, 'discount_amount'),
            currency: data_get($order, 'order_currency_code'),
            currency_rate: '1',
            synced: 0,
            errors: null,
            comment: null
        );

        $orderItems = $order->items->map(fn ($item) => $orderRepository->prepareOrderItemData(
            order_id: data_get($item, 'order_id'),
            sku: data_get($item, 'sku'),
            quantity: data_get($item, 'qty_invoiced') ?: data_get($item, 'qty_ordered'),
            price: data_get($item, 'price'),
            total: data_get($item, 'total'),
            tax: data_get($item, 'tax_amount'),
        ))->toArray();

        $orderAddressData = $orderRepository->prepareOrderAddressData(
            order_id: data_get($order, 'id'),
            firstname: data_get($order, 'shipping_address.first_name'),
            lastname: data_get($order, 'shipping_address.last_name'),
            city: data_get($order, 'shipping_address.city'),
            country: data_get($order, 'shipping_address.country'),
            company: data_get($order, 'shipping_address.company_name'),
            address_line_1: data_get($order, 'shipping_address.address'),
            address_line_2: '',
            postcode: data_get($order, 'shipping_address.postcode', ''),
            custom: data_get($order, 'shipping_address.additional', ''),
        );

        $orderRepository->addOrder(
            $orderData,
            $orderItems,
            $orderAddressData
        );
    }
}
