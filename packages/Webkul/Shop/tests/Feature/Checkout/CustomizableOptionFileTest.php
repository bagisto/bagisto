<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;

it('does not relocate files outside the customer upload directory when managing customizable options', function () {
    Storage::fake();

    /**
     * A file that does not belong to the customer (e.g. a product image) sitting on the disk.
     */
    Storage::put('product/1/victim.png', 'victim-contents');

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $order = Order::factory()->create();

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_type' => get_class($product),
        'additional' => [
            'formatted_customizable_options' => [
                [
                    'id' => 1,
                    'type' => 'file',
                    'label' => ['en' => 'Upload File'],
                    'prices' => [
                        ['label' => 'product/1/victim.png'],
                    ],
                ],
            ],
        ],
    ]);

    app(OrderItemRepository::class)->manageCustomizableOptions($orderItem);

    Storage::assertExists('product/1/victim.png');
    Storage::assertMissing('orders/'.$order->id.'/victim.png');

    expect($orderItem->fresh()->additional['formatted_customizable_options'][0]['prices'][0]['label'])
        ->toBe('product/1/victim.png');
});

it('relocates genuinely uploaded customizable option files into the orders directory', function () {
    Storage::fake();

    $cartId = 42;

    Storage::put("carts/{$cartId}/upload.png", 'upload-contents');

    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $order = Order::factory()->create();

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_type' => get_class($product),
        'additional' => [
            'formatted_customizable_options' => [
                [
                    'id' => 1,
                    'type' => 'file',
                    'label' => ['en' => 'Upload File'],
                    'prices' => [
                        ['label' => "carts/{$cartId}/upload.png"],
                    ],
                ],
            ],
        ],
    ]);

    app(OrderItemRepository::class)->manageCustomizableOptions($orderItem);

    Storage::assertMissing("carts/{$cartId}/upload.png");
    Storage::assertExists('orders/'.$order->id.'/upload.png');

    expect($orderItem->fresh()->additional['formatted_customizable_options'][0]['prices'][0]['label'])
        ->toBe('orders/'.$order->id.'/upload.png');
});
