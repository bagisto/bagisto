<?php

namespace Tests\Unit\Sales\Order;

use Helper\Bagisto;
use UnitTester;
use Webkul\Product\Models\ProductOrderedInventory;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Repositories\OrderItemRepository;

class OrderItemRepositoryCest
{
    /**
     * Order item repository.
     *
     * @var Webkul\Sales\Repositories\OrderItemRepository
     */
    private $repository;

    public function _before()
    {
        $reflection = new \ReflectionClass(OrderItemRepository::class);

        $property = $reflection->getProperty('model');
        $property->setAccessible(true);

        $this->repository = $reflection->newInstanceWithoutConstructor();
    }

    public function testUpdateProductOrderedInventories(UnitTester $I)
    {
        /**
         * Having 10 quantity in inventory.
         */
        $product = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, [
            'productInventory' => ['qty' => 10],
        ]);

        /**
         * 2 quantities are on hold.
         */
        $productOrderedInventory = $I->have(ProductOrderedInventory::class, [
            'product_id' => $product->id,
            'qty'        => 2,
        ]);

        /**
         * 5 quantities are shipped.
         */
        $orderItem1 = $I->have(OrderItem::class, [
            'product_id'  => $product->id,
            'qty_ordered' => 5,
            'qty_shipped' => 5,
        ]);

        /**
         * 2 quantities are in queue.
         */
        $orderItem2 = $I->have(OrderItem::class, [
            'product_id'  => $product->id,
            'qty_ordered' => 2,
            'qty_shipped' => 0,
        ]);

        /**
         * Now testing the repository method with shipped one cancelled.
         */
        $this->repository->updateProductOrderedInventories($orderItem1);
        $productOrderedInventory->refresh();
        $I->assertNotEquals(0, $productOrderedInventory->qty);
        $I->assertEquals(2, $productOrderedInventory->qty);

        /**
         * Now testing the repository method with pending one cancelled.
         */
        $this->repository->updateProductOrderedInventories($orderItem2);
        $productOrderedInventory->refresh();
        $I->assertEquals(0, $productOrderedInventory->qty);
    }
}
