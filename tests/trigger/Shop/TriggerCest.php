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
    private $root2Category;
    private $childOfRoot2Category;

    private $parentCategoryAttributes;
    private $categoryAttributes;
    private $root2CategoryAttributes;
    private $childOfRoot2CategoryAttributes;

    private $parentCategoryName;
    private $categoryName;
    private $root2CategoryName;
    private $childOfRoot2CategoryName;


    /** @var Locale $localeEn */
    private $localeEn;
    /** @var Locale $localeDe */
    private $localeDe;

    public function _before(UnitTester $I)
    {
        $this->faker = Factory::create();

        $rootCategoryTranslation = $I->grabRecord(CategoryTranslation::class, [
            'slug' => 'root',
            'locale' => 'en',
        ]);
        $rootCategory = $I->grabRecord(Category::class, [
            'id' => $rootCategoryTranslation->category_id,
        ]);

        $this->parentCategoryName = $this->faker->word;
        $this->categoryName = $this->faker->word . $this->faker->randomDigit;
        $this->root2CategoryName = $this->faker->word . $this->faker->randomDigit;
        $this->childOfRoot2CategoryName = $this->faker->word . $this->faker->randomDigit;

        $this->localeEn = $I->grabRecord(Locale::class, [
            'code' => 'en',
        ]);
        $this->localeDe = $I->have(Locale::class, [
            'code' => 'de',
            'name' => 'German',
        ]);

        $this->parentCategoryAttributes = [
            'parent_id'           => $rootCategory->id,
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

        $this->parentCategory = $I->make(Category::class, $this->parentCategoryAttributes)->first();
        $rootCategory->appendNode($this->parentCategory);
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

        $this->category = $I->make(Category::class, $this->categoryAttributes)->first();
        $this->parentCategory->appendNode($this->category);
        $I->assertNotNull($this->category);


        $this->root2CategoryAttributes = [
            'position'            => 1,
            'status'              => 1,
            'parent_id'           => null,
            $this->localeEn->code => [
                'name' => $this->root2CategoryName,
                'slug' => strtolower($this->root2CategoryName),
                'description' => $this->root2CategoryName,
                'locale_id' => $this->localeEn->id,
            ],
            $this->localeDe->code => [
                'name' => $this->root2CategoryName,
                'slug' => strtolower($this->root2CategoryName),
                'description' => $this->root2CategoryName,
                'locale_id' => $this->localeDe->id,
            ],
        ];

        $this->root2Category = $I->make(Category::class, $this->root2CategoryAttributes)->first();
        $this->root2Category->save();

        $I->assertNotNull($this->root2Category);
        $I->assertNull($this->root2Category->parent_id);
        $I->assertGreaterThan($rootCategory->_rgt, $this->root2Category->_lft);

        $this->childOfRoot2CategoryAttributes = [
            'position'            => 1,
            'status'              => 1,
            'parent_id'           => $this->root2Category->id,
            $this->localeEn->code => [
                'name' => $this->childOfRoot2CategoryName,
                'slug' => strtolower($this->childOfRoot2CategoryName),
                'description' => $this->childOfRoot2CategoryName,
                'locale_id' => $this->localeEn->id,
            ],
            $this->localeDe->code => [
                'name' => $this->childOfRoot2CategoryName,
                'slug' => strtolower($this->childOfRoot2CategoryName),
                'description' => $this->childOfRoot2CategoryName,
                'locale_id' => $this->localeDe->id,
            ],
        ];

        $this->childOfRoot2Category = $I->make(Category::class, $this->childOfRoot2CategoryAttributes)->first();
        $this->root2Category->appendNode($this->childOfRoot2Category);

        $I->assertNotNull($this->childOfRoot2Category);
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
        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->root2Category->id,
            'name' => $this->root2CategoryName,
            'locale' => $this->localeEn->code,
            'url_path' => '',
        ]);

        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->childOfRoot2Category->id,
            'name' => $this->childOfRoot2CategoryName,
            'locale' => $this->localeDe->code,
            'url_path' => strtolower($this->childOfRoot2CategoryName)
        ]);
        $I->seeRecord(CategoryTranslation::class, [
            'category_id' => $this->childOfRoot2Category->id,
            'name' => $this->childOfRoot2CategoryName,
            'locale' => $this->localeEn->code,
            'url_path' => strtolower($this->childOfRoot2CategoryName)
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
