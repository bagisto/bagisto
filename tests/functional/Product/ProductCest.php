<?php


namespace Tests\Functional\Product;

use FunctionalTester;
use Faker\Factory;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Core\Models\Locale;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;

class ProductCest
{
    /** @var Factory $faker */
    private $faker;

    /** @var Attribute $attributeBrand */
    private $attributeBrand;
    /** @var AttributeOption $attributeBrandDefaultOption */
    private $attributeBrandDefaultOption;
    /** @var AttributeOption $attributeBrandOption */
    private $attributeBrandOption;

    public function _before(FunctionalTester $I)
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

        $this->attributeBrandDefaultOption = $I->have(AttributeOption::class,
            $defaultAttributeOptionAttributes);
        $this->attributeBrandOption = $I->have(AttributeOption::class, [
            'attribute_id' => $this->attributeBrand->id,
        ]);

    }

    public function selectEmptyAttributeOptionOnProductCreation(FunctionalTester $I)
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.catalog.products.create');
        $I->see(__('admin::app.catalog.products.add-title'), 'h1');

        $I->selectOption('select#type', 'simple');

        $attributeFamily = $I->grabRecord(AttributeFamily::class, [
            'code' => 'default',
        ]);

        $I->selectOption('select#attribute_family_id', $attributeFamily->id);

        $sku = $this->faker->uuid;

        $I->fillField('sku', $sku);
        $I->click(__('admin::app.catalog.products.save-btn-title'));

        $I->seeInSource('Product created successfully.');

        $I->seeCurrentRouteIs('admin.catalog.products.edit');

        $productTitle = $this->faker->word;
        $productUrlKey = $this->faker->slug;

        $I->fillField('name', $productTitle);
        $I->fillField('url_key', $productUrlKey);
        $I->selectOption($this->attributeBrand->code,
            $this->attributeBrandDefaultOption->id);
        $I->fillField('price', $this->faker->randomFloat(2));
        $I->fillField('weight', $this->faker->randomDigit);

        $I->fillField('#short_description', $this->faker->paragraph(1, true));
        $I->fillField('#description', $this->faker->paragraph(5, true));

        $I->click(__('admin::app.catalog.products.save-btn-title'));

        $I->seeInSource('Product updated successfully.');
        $I->seeCurrentRouteIs('admin.catalog.products.index');

        $product = $I->grabRecord(Product::class, [
            'sku'                 => $sku,
            'type'                => 'simple',
            'attribute_family_id' => $attributeFamily->id,
        ]);

        $I->seeRecord(ProductAttributeValue::class, [
            'product_id'    => $product->id,
            'attribute_id'  => $this->attributeBrand->id,
            'integer_value' => $this->attributeBrandDefaultOption->id,
            'text_value'    => null,
        ]);
    }
}
