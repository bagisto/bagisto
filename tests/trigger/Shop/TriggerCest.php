<?php

namespace Tests\Unit\Shop;

use Faker\Factory;
use UnitTester;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Core\Models\Locale;

class TriggerCest
{
    private $faker;

    private $parentCategory;
    private $category;

    private $parentCategoryAttributes;
    private $categoryAttributes;

    private $parentCategoryName;
    private $categoryName;

    /** @var Locale $localeEn */
    private $localeEn;
    /** @var Locale $localeDe */
    private $localeDe;

    public function _before(UnitTester $I)
    {
        $this->faker = Factory::create();

        $this->parentCategoryName = $this->faker->word;
        $this->categoryName = $this->faker->word . $this->faker->randomDigit;

        $this->localeEn = $I->grabRecord(Locale::class, [
            'code' => 'en',
        ]);
        $this->localeDe = $I->have(Locale::class, [
            'code' => 'de',
            'name' => 'German',
        ]);

        $this->parentCategoryAttributes = [
            'parent_id'           => 1,
            'position'            => 1,
            'status'              => 1,
            $this->localeEn->code => [
                'name' => $this->parentCategoryName,
                'slug' => strtolower($this->parentCategoryName),
                'description' => $this->parentCategoryName,
                'locale_id' => $this->localeEn->id,
            ],
            $this->localeDe->code => [
                'name' => $this->parentCategoryName,
                'slug' => strtolower($this->parentCategoryName),
                'description' => $this->parentCategoryName,
                'locale_id' => $this->localeDe->id,
            ],
        ];

        $this->parentCategory = $I->have(Category::class, $this->parentCategoryAttributes);
        $I->assertNotNull($this->parentCategory);

        $this->categoryAttributes = [
            'position'            => 1,
            'status'              => 1,
            'parent_id'           => $this->parentCategory->id,
            $this->localeEn->code => [
                'name' => $this->categoryName,
                'slug' => strtolower($this->categoryName),
                'description' => $this->categoryName,
                'locale_id' => $this->localeEn->id,
            ],
            $this->localeDe->code => [
                'name' => $this->categoryName,
                'slug' => strtolower($this->categoryName),
                'description' => $this->categoryName,
                'locale_id' => $this->localeDe->id,
            ],
        ];

        $this->category = $I->have(Category::class, $this->categoryAttributes);
        $I->assertNotNull($this->category);
    }

    public function testInsertTriggerOnCategoryTranslationsTable(UnitTester $I)
    {
        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->parentCategory->id,
            'name' => $this->parentCategoryName,
            'locale' => $this->localeEn->code,
            'url_path' => strtolower($this->parentCategoryName)
        ]);
        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->parentCategory->id,
            'name' => $this->parentCategoryName,
            'locale' => $this->localeDe->code,
            'url_path' => strtolower($this->parentCategoryName)
        ]);

        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $this->categoryName,
            'locale' => $this->localeEn->code,
            'url_path' => strtolower($this->parentCategoryName) . '/' . strtolower($this->categoryName)
        ]);
        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $this->categoryName,
            'locale' => $this->localeDe->code,
            'url_path' => strtolower($this->parentCategoryName) . '/' . strtolower($this->categoryName)
        ]);
    }

    public function testUpdateTriggersOnCategoryTranslationsTable(UnitTester $I)
    {
        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $this->categoryName,
            'slug' => strtolower($this->categoryName),
            'locale' => $this->localeEn->code,
            'url_path' => strtolower($this->parentCategoryName) . '/' . strtolower($this->categoryName),
        ]);

        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $this->categoryName,
            'slug' => strtolower($this->categoryName),
            'locale' => $this->localeDe->code,
            'url_path' => strtolower($this->parentCategoryName) . '/' . strtolower($this->categoryName),
        ]);

        $newCategoryName = $this->faker->word;
        $this->categoryAttributes[$this->localeDe->code]['name'] = $newCategoryName;
        $this->categoryAttributes[$this->localeDe->code]['slug'] = strtolower($newCategoryName);
        $I->assertTrue($this->category->update($this->categoryAttributes));
        $this->category->refresh();

        $I->dontSeeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $newCategoryName,
            'slug' => strtolower($this->categoryName),
            'locale' => $this->localeEn->code,
            'url_path' => strtolower($this->parentCategoryName) . '/' . strtolower($this->categoryName),
        ]);

        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $newCategoryName,
            'slug' => strtolower($newCategoryName),
            'locale' => $this->localeDe->code,
            'url_path' => strtolower($this->parentCategoryName) . '/' . strtolower($newCategoryName),
        ]);
    }

    public function testInsertTriggersOnCategoriesTable(UnitTester $I)
    {
        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->parentCategory->id,
            'name' => $this->parentCategoryName,
            'slug' => strtolower($this->parentCategoryName),
            'locale' => $this->localeEn->code,
            'url_path' => strtolower($this->parentCategoryName),
        ]);

        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $this->categoryName,
            'slug' => strtolower($this->categoryName),
            'locale' => $this->localeEn->code,
            'url_path' => strtolower($this->parentCategoryName) . '/' . $this->categoryName,
        ]);
    }

    public function testUpdateTriggersOnCategoriesTable(UnitTester $I)
    {
        $I->seeRecord(Category::class, [
            'id' => $this->category->id,
            'parent_id' => $this->parentCategory->id,
        ]);

        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $this->categoryName,
            'slug' => strtolower($this->categoryName),
            'locale' => $this->localeEn->code,
            'url_path' => strtolower($this->parentCategoryName) . '/' . strtolower($this->categoryName),
        ]);

        $category2Name = $this->faker->word;
        $category2Attributes = [
            'position' => 1,
            'status' => 1,
            'parent_id' => $this->parentCategory->id,
            $this->localeEn->code => [
                'name' => $category2Name,
                'slug' => strtolower($category2Name),
                'description' => $category2Name,
                'locale_id' => $this->localeEn->id,
            ],
        ];

        $category2 = $I->have(Category::class, $category2Attributes);
        $I->assertNotNull($category2);

        $this->categoryAttributes['parent_id'] = $category2->id;
        $I->assertTrue($this->category->update($this->categoryAttributes));
        $this->category->refresh();

        $expectedUrlPath = strtolower($this->parentCategoryName) . '/'
            . strtolower($category2Name) . '/'
            . strtolower($this->categoryName);
        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->category->id,
            'name' => $this->categoryName,
            'slug' => strtolower($this->categoryName),
            'locale' => $this->localeEn->code,
            'url_path' => $expectedUrlPath,
        ]);
    }
}
