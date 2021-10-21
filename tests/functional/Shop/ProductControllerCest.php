<?php

namespace Tests\Functional\Shop;

use Faker\Factory;
use Faker\Generator;
use FunctionalTester;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

class ProductControllerCest
{
    /** @var Generator */
    private $faker;

    public function _before(FunctionalTester $I)
    {
        $this->faker = Factory::create();
    }

    public function testCreate(FunctionalTester $I)
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.catalog.products.index');
        $I->click(__('admin::app.catalog.products.add-product-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeCurrentRouteIs('admin.catalog.products.create');

        $I->click(__('admin::app.catalog.products.save-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeFormHasErrors();

        $testSku = $this->faker->uuid;
        $I->selectOption('//select[@id="attribute_family_id"]', 'Default');
        $I->fillField('//input[@id="sku"]', $testSku);
        $I->click(__('admin::app.catalog.products.save-btn-title'), '//*[contains(@class, "page-action")]');
        $I->dontSeeFormErrors();
        $I->seeCurrentRouteIs('admin.catalog.products.edit');
        $I->seeRecord(Product::class, ['sku' => $testSku]);

        $I->click(__('admin::app.catalog.products.save-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeFormHasErrors();

        $testName = $this->faker->name;
        $testUrlKey = $testName;
        $testDescription = $this->faker->sentence;
        $testDescriptionShop = $this->faker->sentence;
        $testPrice = $this->faker->randomFloat(2, 1, 100);
        $testWeight = $this->faker->numberBetween(1, 20);
        $I->fillField('//input[@id="name"]', $testName);
        $I->fillField('//input[@id="url_key"]', $testUrlKey);
        $I->fillField('//textarea[@id="description"]', $testDescription);
        $I->fillField('//textarea[@id="short_description"]', $testDescriptionShop);
        $I->fillField('//input[@id="price"]', $testPrice);
        $I->fillField('//input[@id="weight"]', $testWeight);
        $I->click(__('admin::app.catalog.products.save-btn-title'), '//*[contains(@class, "page-action")]');
        $I->dontSeeFormErrors();
        $I->seeCurrentRouteIs('admin.catalog.products.index');
        $product = $I->grabRecord(Product::class, ['sku' => $testSku]);
        $I->seeRecord(ProductFlat::class, [
            'sku'               => $testSku,
            'name'              => $testName,
            'description'       => $testDescription,
            'short_description' => $testDescriptionShop,
            'url_key'           => $testUrlKey,
            'price'             => $testPrice,
            'weight'            => $testWeight,
            'product_id'        => $product->id,
        ]);
    }
}
