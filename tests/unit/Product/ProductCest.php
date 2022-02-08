<?php

namespace Tests\Unit\Product;

use Helper\Bagisto;
use UnitTester;
use Webkul\Product\Repositories\ProductInventoryRepository;

class ProductCest
{
    /**
     * Test product inventory updation.
     *
     * @param  UnitTester  $I
     * @return void
     */
    public function testProductInventoryUpdation(UnitTester $I): void
    {
        $product = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, [], ['simple']);

        $updatedInventoriesQty = $this->getRandomUpdatedInventoriesQty($I, $product);

        app(ProductInventoryRepository::class)->saveInventories([
            'inventories' => $updatedInventoriesQty,
        ], $product);

        $product->refresh();

        $I->assertEquals(array_sum($updatedInventoriesQty), $product->inventories->sum('qty'));
    }

    /**
     * Test old quantities.
     *
     * @param  UnitTester  $I
     * @return void
     */
    public function testProductInventoriesQty(UnitTester $I): void
    {
        $product = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, [], ['simple']);

        $oldInventoriesQty = $product->inventories->pluck('qty', 'inventory_source_id')->toArray();

        $oldTotalQuantity = $product->inventories->sum('qty');

        $I->assertEquals($oldTotalQuantity, array_sum($oldInventoriesQty));
    }

    /**
     * Get random inventories qty for product.
     *
     * @param  UnitTester  $I
     * @param  \Webkul\Product\Models\Product  $product
     * @return array
     */
    private function getRandomUpdatedInventoriesQty(UnitTester $I, $product): array
    {
        $oldInventoriesQty = $product->inventories->pluck('qty', 'inventory_source_id');

        $updatedInventoriesQty = [];

        foreach ($oldInventoriesQty as $id => $oldInventoryQty) {
            $updatedInventoriesQty[$id] = $I->fake()->numberBetween(500, 2000);
        }

        return $updatedInventoriesQty;
    }
}
