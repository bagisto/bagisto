<?php

namespace Webkul\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Marketing\Models\URLRewrite;

class UrlRewriteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = URLRewrite::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        $entityTypes = ['product', 'category', 'cms_page'];
        $redirecType = [302, 301];

        return [
            'entity_type'    => $entityTypes[array_rand($entityTypes)],
            'request_path'   => $this->faker->url,
            'target_path'    => $this->faker->url,
            'redirect_type'  => $redirecType[array_rand($redirecType)],
            'locale'         => core()->getCurrentLocale()->code,
        ];
    }
}
