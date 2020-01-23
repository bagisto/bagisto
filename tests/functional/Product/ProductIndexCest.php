<?php

namespace Tests\Functional\Product;

use FunctionalTester;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\Locale;
use Webkul\Product\Models\Product;
use Faker\Factory;
use Webkul\Product\Models\ProductAttributeValue;

class ProductIndexCest
{
    private $faker;

    private $defaultChannel;
    private $secondChannel;

    private $localeEn;
    private $localeDe;

    private $productDefaultChannel;
    private $productSecondChannel;

    private $productNameDefaultChannel = [];
    private $productNameSecondChannel = [];

    public function _before(FunctionalTester $I)
    {
        $this->faker = Factory::create();

        $this->localeEn = $I->grabRecord(Locale::class, [
            'code' => 'en',
        ]);
        $I->assertNotNull($this->localeEn);

        $this->localeDe = $I->have(Locale::class, [
            'code' => 'de',
            'name' => 'German',
        ]);

        $this->defaultChannel = $I->grabRecord(Channel::class, [
            'code' => 'default',
        ]);
        $I->assertNotNull($this->defaultChannel);

        $currency = $I->grabRecord(Currency::class, [
            'code' => 'USD',
        ]);

        $rootCategoryTranslation = $I->grabRecord(CategoryTranslation::class, [
            'slug' => 'root',
            'locale' => 'en',
        ]);
        $I->assertNotNull($rootCategoryTranslation);

        $this->productDefaultChannel = $I->have(Product::class);

        $this->productNameDefaultChannel['en'] = $I->have(ProductAttributeValue::class, [
            'product_id' => $this->productDefaultChannel->id,
            'locale' => $this->localeEn->code,
            'channel' => $this->defaultChannel->code,
        ], 'name');

        $this->secondChannel = $I->have(Channel::class, [
            /*'locales' => [
                $this->localeEn->id,
                $this->localeDe->id,
            ],*/
            'default_locale_id' => $this->localeEn->id,
            /*'currencies' => [
                $currency->id,
            ],*/
            'base_currency_id' => $currency->id,
            'root_category_id' => $rootCategoryTranslation->category_id,
        ]);

        $this->productSecondChannel = $I->have(Product::class);

        $this->productNameSecondChannel['en'] = $I->have(ProductAttributeValue::class, [
            'product_id' => $this->productSecondChannel->id,
            'locale' => $this->localeEn->code,
            'channel' => $this->secondChannel->code,
        ], 'name');
        $this->productNameSecondChannel['de'] = $I->have(ProductAttributeValue::class, [
            'product_id' => $this->productSecondChannel->id,
            'locale' => $this->localeDe->code,
            'channel' => $this->secondChannel->code,
        ], 'name');
    }

    public function testProductIndex(FunctionalTester $I)
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.catalog.products.index');

        $I->see(__('admin::app.catalog.products.title'), 'h1');

        $I->see($this->productNameDefaultChannel['en']->text_value, 'td');
        $I->see($this->productNameSecondChannel['en']->text_value, 'td');
    }

    public function testProductIndexWithChannelFilter(FunctionalTester $I)
    {

    }

    public function testProductIndexWithLocaleFilter(FunctionalTester $I)
    {

    }

    public function testProductIndexWithChannelAndLocaleFilter(FunctionalTester $I)
    {

    }
}
