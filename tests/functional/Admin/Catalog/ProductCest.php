<?php

namespace Tests\Functional\Admin\Catalog;

use Faker\Factory;
use FunctionalTester;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Core\Models\Locale;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;

class ProductCest
{
    /**
     * Factory $faker
     *
     * @var Faker\Factory
     */
    private $faker;

    /**
     * Attribute $attributeBrand
     *
     * @var Webkul\Attribute\Models\Attribute
     */
    private $attributeBrand;

    /**
     * AttributeOption $attributeBrandDefaultOption
     *
     * @var Webkul\Attribute\Models\AttributeOption
     */
    private $attributeBrandDefaultOption;

    /**
     * AttributeOption $attributeBrandOption
     *
     * @var Webkul\Attribute\Models\AttributeOption
     */
    private $attributeBrandOption;

    public function _before(FunctionalTester $I): void
    {
        $this->faker = Factory::create();

        $this->attributeBrand = $I->grabRecord(Attribute::class, [
            'code'       => 'brand',
            'admin_name' => 'Brand',
        ]);

        $locales = Locale::pluck('code')->all();

        $defaultAttributeOptionAttributes = [
            'attribute_id' => $this->attributeBrand->id,
            'admin_name'   => 'no-brand',
            'sort_order'   => 0,
        ];

        foreach ($locales as $locale) {
            $defaultAttributeOptionAttributes[$locale] = [
                'label' => '',
            ];
        }

        $this->attributeBrandDefaultOption = $I->have(AttributeOption::class, $defaultAttributeOptionAttributes);
        $this->attributeBrandOption = $I->have(AttributeOption::class, [
            'attribute_id' => $this->attributeBrand->id,
        ]);
    }

    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.catalog.products.index');
        $I->seeCurrentRouteIs('admin.catalog.products.index');

        $I->sendAjaxGetRequest(route('admin.catalog.products.index'));
        $I->seeResponseCodeIsSuccessful();
    }

    public function selectEmptyAttributeOptionOnProductCreation(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.catalog.products.index');

        $I->click(__('admin::app.catalog.products.add-product-btn-title'), '//*[contains(@class, "page-action")]');
        $I->seeCurrentRouteIs('admin.catalog.products.create');

        $I->selectOption('type', 'simple');

        $attributeFamily = $I->grabRecord(AttributeFamily::class, [
            'code' => 'default',
        ]);

        $I->selectOption('attribute_family_id', (string) $attributeFamily->id);

        $sku = $this->faker->randomNumber(3);

        $I->fillField('sku', $sku);
        $I->click(__('admin::app.catalog.products.save-btn-title'));
        $I->seeInSource('Product created successfully.');
        $I->seeCurrentRouteIs('admin.catalog.products.edit');

        $productTitle = $this->faker->word;
        $productUrlKey = $this->faker->slug;

        $I->fillField('name', $productTitle);
        $I->fillField('url_key', $productUrlKey);
        $I->selectOption($this->attributeBrand->code, (string) $this->attributeBrandDefaultOption->id);
        $I->fillField('price', $this->faker->randomFloat(2));
        $I->fillField('weight', $this->faker->randomDigit);
        $I->fillField('short_description', $this->faker->paragraph(1, true));
        $I->fillField('description', $this->faker->paragraph(5, true));

        $I->click(__('admin::app.catalog.products.save-btn-title'));

        $I->seeInSource('Product updated successfully.');
        $I->seeCurrentRouteIs('admin.catalog.products.index');

        $product = $I->grabRecord(Product::class, [
            'sku'                 => $sku,
            'type'                => 'simple',
            'attribute_family_id' => $attributeFamily->id,
        ]);

        $productAttribute = $I->grabRecord(ProductAttributeValue::class, [
            'product_id'    => $product->id,
            'attribute_id'  => $this->attributeBrand->id,
            'integer_value' => $this->attributeBrandDefaultOption->id,
        ]);

        $I->seeRecord(ProductAttributeValue::class, [
            'product_id'    => $product->id,
            'attribute_id'  => $this->attributeBrand->id,
            'integer_value' => $this->attributeBrandDefaultOption->id,
        ]);

        $I->assertNull($productAttribute->text_value);
    }
}
