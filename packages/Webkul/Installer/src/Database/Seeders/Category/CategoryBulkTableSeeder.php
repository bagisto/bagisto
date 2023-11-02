<?php

namespace Webkul\Installer\Database\Seeders\Category;

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Webkul\Category\Repositories\CategoryRepository;

/*
 * Category bulk table seeder.
 *
 * Command: php artisan db:seed --class=Webkul\\Category\\Database\\Seeders\\CategoryBulkTableSeeder
 *
 * This seeder has not included anywhere just for development purpose.
 */
class CategoryBulkTableSeeder extends Seeder
{
    private $numberOfParentCategories = 10;

    private $numberOfChildCategories = 50;

    public function __construct(
        Faker $faker,
        CategoryRepository $categoryRepository
    ) {
        $this->faker = $faker;
        $this->categoryRepository = $categoryRepository;
    }

    public function run()
    {
        for ($i = 0; $i < $this->numberOfParentCategories; $i++) {
            $createdCategory = $this->categoryRepository->create([
                'slug'        => $this->faker->slug,
                'name'        => $this->faker->firstName,
                'description' => $this->faker->text(),
                'parent_id'   => 1,
                'status'      => 1,
            ]);

            if ($createdCategory) {
                for ($j = 0; $j < $this->numberOfChildCategories; $j++) {

                    $this->categoryRepository->create([
                        'slug'        => $this->faker->slug,
                        'name'        => $this->faker->firstName,
                        'description' => $this->faker->text(),
                        'parent_id'   => $createdCategory->id,
                        'status'      => 1,
                    ]);
                }
            }
        }
    }
}
