<?php

namespace Tests\Functional\Product;

use FunctionalTester;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;
use Webkul\Core\Helpers\Laravel5Helper;
use Webkul\Product\Models\ProductInventory;
use Webkul\Product\Models\ProductAttributeValue;

class ProductCopyCest
{

    public function testProductCopy(FunctionalTester $I)
    {
        $I->loginAsAdmin();

        $originalName = $I->fake()->name;

        $original = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, [
            'productInventory' => [
                'qty' => 10,
            ],
            'attributeValues'  => [
                'name' => $originalName,
            ],
        ]);

        $count = count(Product::all());

        $I->amOnAdminRoute('admin.catalog.products.copy', ['id' => $original->id], false);

        $copiedProduct = $I->grabRecord(Product::class, [
            'id'                  => $original->id + 1,
            'parent_id'           => $original->parent_id,
            'attribute_family_id' => $original->attribute_family_id,
        ]);

        $I->seeRecord(ProductAttributeValue::class, [
            'attribute_id' => 2,
            'product_id'   => $copiedProduct->id,
            'text_value'   => 'Copy of ' . $originalName,
        ]);

        // url_key
        $I->seeRecord(ProductAttributeValue::class, [
            'attribute_id' => 3,
            'product_id'   => $copiedProduct->id,
            'text_value'   => 'copy-of-' . $original->url_key,
        ]);

        // sku
        $I->seeRecord(ProductAttributeValue::class, [
            'attribute_id' => 1,
            'product_id'   => $copiedProduct->id,
        ]);

        // sku
        $I->dontSeeRecord(ProductAttributeValue::class, [
            'attribute_id' => 1,
            'product_id'   => $copiedProduct->id,
            'text_value'   => $original->sku,
        ]);

        // status
        $I->seeRecord(ProductAttributeValue::class, [
            'attribute_id'  => 8,
            'boolean_value' => 0,
        ]);

        $I->seeRecord(ProductInventory::class, [
            'product_id' => $copiedProduct->id,
            'qty'        => 10,
        ]);

        $I->seeRecord(ProductFlat::class, [
            'product_id' => $copiedProduct->id,
            'name'       => 'Copy of ' . $originalName,
        ]);

        $I->assertCount($count + 1, Product::all());

        $I->seeResponseCodeIsSuccessful();
    }
}
