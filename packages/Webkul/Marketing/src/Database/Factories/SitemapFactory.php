<?php

namespace Webkul\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Sitemap\Models\Sitemap;

class SitemapFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sitemap::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'file_name' => strtolower(fake()->word()).'.xml',
            'path'      => '/',
        ];
    }
}
