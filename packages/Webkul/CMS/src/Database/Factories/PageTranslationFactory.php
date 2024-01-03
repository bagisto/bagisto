<?php

namespace Webkul\CMS\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\CMS\Models\PageTranslation;

class PageTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PageTranslation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'html_content'     => substr($this->faker->paragraph, 0, 50),
            'locale'           => core()->getCurrentLocale()->code,
            'meta_description' => $this->faker->title(),
            'meta_title'       => $this->faker->title(),
            'page_title'       => $this->faker->title(),
            'url_key'          => $this->faker->slug(),
        ];
    }
}
