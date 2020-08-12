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

    public function _before(FunctionalTester $I)
    {
        $I->loginAsAdmin();
    }

    public function testSkipAttributes(FunctionalTester $I)
    {
        config(['products.skipAttributesOnCopy' => ['name', 'inventories']]);

        $original = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, [
            'productInventory' => [
                'qty' => 10,
            ],
            'attributeValues'  => [
                'name' => 'Original',
            ],
        ]);

        $I->amOnAdminRoute('admin.catalog.products.copy', ['id' => $original->id], false);

        // test attribute is skipped:
        $attr = $I->dontSeeRecord(ProductAttributeValue::class, [
            'attribute_id' => 2, // name
            'product_id'   => $original->id + 1,
        ]);

        // test relation is skipped:
        $I->dontSeeRecord(ProductInventory::class, [
            'product_id' => $original->id + 1,
            'qty'        => 10,
        ]);
    }

    public function testBlockProductCopy(FunctionalTester $I)
    {
        $original = $I->haveProduct(Laravel5Helper::BOOKING_EVENT_PRODUCT, []);

        $I->amOnAdminRoute('admin.catalog.products.copy', ['id' => $original->id], false);

        $I->seeInSource('Products of type booking can not be copied');
    }

    public function testProductCopy(FunctionalTester $I)
    {
        // set this config value to true to make it testable. It defaults to false.
        config(['products.linkProductsOnCopy' => true]);

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

        $attr = $I->grabRecord(ProductAttributeValue::class, [
            'attribute_id' => 2,
            'product_id'   => $copiedProduct->id,
        ]);
        $I->assertStringStartsWith('Copy of ' . $originalName, $attr->text_value);

        // url_key
        $attr = $I->grabRecord(ProductAttributeValue::class, [
            'attribute_id' => 3,
            'product_id'   => $copiedProduct->id,
        ]);
        $I->assertStringStartsWith('copy-of-' . $original->url_key, $attr->text_value);

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

        $I->seeRecord('product_relations', [
            'parent_id' => $original->id,
            'child_id'  => $copiedProduct->id,
        ]);

        $flat = $I->grabRecord(ProductFlat::class, [
            'product_id' => $copiedProduct->id,
        ]);
        $I->assertStringStartsWith('Copy of ' . $originalName, $flat->name);

        $I->assertCount($count + 1, Product::all());

        $I->seeResponseCodeIsSuccessful();
    }
}
