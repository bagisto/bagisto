<?php

namespace Webkul\Core\Database\Factories;

use Faker\Generator as Faker;
use Webkul\Category\Models\Category;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Currency;
use Webkul\Core\Models\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Channel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \JsonException
     */
    public function definition(): array
    {
        $seoTitle = $this->faker->word;
        $seoDescription = $this->faker->words(10, true);
        $seoKeywords = $this->faker->words(3, true);

        $seoData = [
            'meta_title'       => $seoTitle,
            'meta_description' => $seoDescription,
            'meta_keywords'    => $seoKeywords,
        ];

        return [
            'code'              => $this->faker->unique()->word,
            'name'              => $this->faker->word,
            'default_locale_id' => Locale::factory(),
            'base_currency_id'  => Currency::factory(),
            'root_category_id'  => Category::factory(),
            'home_seo'          => json_encode($seoData, JSON_THROW_ON_ERROR),
        ];
    }
}