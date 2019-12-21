<?php

namespace Tests\Unit\Shop;

use UnitTester;
use Webkul\Category\Models\Category;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Locale;

class DatabaseLogicCest
{
    private $faker;

    /** @var Locale $localeEn */
    private $localeEn;
    /** @var Locale $localeDe */
    private $localeDe;
    
    public function _before(UnitTester $I)
    {
        $this->faker = Factory::create();

        $this->localeEn = $I->grabRecord(Locale::class, [
            'code' => 'en',
        ]);

        $this->localeDe = $I->have(Locale::class, [
            'code' => 'de',
            'name' => 'German',
        ]);

        $I->assertNotNull($this->localeDe);
    }

    public function testGetUrlPathOfCategory(UnitTester $I)
    {
        $parentCategoryName = $this->faker->word;
        
        $parentCategoryAttributes = [
            'parent_id'           => 1,
            'position'            => 1,
            'status'              => 1,
            $this->localeEn->code => [
                'name' => $parentCategoryName,
                'slug' => strtolower($parentCategoryName),
                'description' => $parentCategoryName,
                'locale_id' => $this->localeEn->id,
            ],
            $this->localeDe->code => [
                'name' => $parentCategoryName,
                'slug' => strtolower($parentCategoryName),
                'description' => $parentCategoryName,
                'locale_id' => $this->localeDe->id,
            ],
        ];

        $parentCategory = $I->have(Category::class, $parentCategoryAttributes);
        $I->assertNotNull($parentCategory);

        $categoryName = $this->faker->word; 
        $categoryAttributes = [
            'position'            => 1,
            'status'              => 1,
            'parent_id'           => $parentCategory->id,
            $this->localeEn->code => [
                'name' => $categoryName,
                'slug' => strtolower($categoryName),
                'description' => $categoryName,
                'locale_id' => $this->localeEn->id,
            ],
            $this->localeDe->code => [
                'name' => $categoryName,
                'slug' => strtolower($categoryName),
                'description' => $categoryName,
                'locale_id' => $this->localeDe->id,
            ],
        ];

        $category = $I->have(Category::class, $categoryAttributes);
        $I->assertNotNull($category);

        $sqlStoredFunction = 'SELECT get_url_path_of_category(:category_id, :locale_code) AS url_path;';

        $urlPathQueryResult = DB::selectOne($sqlStoredFunction, [
            'category_id' => $parentCategory->id,
            'locale_code' => $this->localeEn->code,
        ]);
        $I->assertNotNull($urlPathQueryResult->url_path);
        $I->assertEquals(strtolower($parentCategoryName), $urlPathQueryResult->url_path);

        $urlPathQueryResult = DB::selectOne($sqlStoredFunction, [
            'category_id' => $category->id,
            'locale_code' => $this->localeEn->code,
        ]);
        $I->assertNotNull($urlPathQueryResult->url_path);

        $expectedUrlPath = strtolower($parentCategoryName) . '/' . strtolower($categoryName);
        $I->assertEquals($expectedUrlPath, $urlPathQueryResult->url_path);
    }
}
