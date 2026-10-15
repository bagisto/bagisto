<?php

namespace Webkul\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Marketing\Models\SearchTerm;

class SearchTermFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SearchTerm::class;

    /**
     * Define the model's default state. A term is unique within a channel and locale, so the
     * word carries a discriminator rather than colliding with the next row the factory makes.
     */
    public function definition()
    {
        $terms = ['jackets', 'phone', 'computers', 'electronics'];

        return [
            'term' => $terms[array_rand($terms)].' '.$this->faker->unique()->numerify('######'),
            'redirect_url' => $this->faker->url,
            'channel_id' => core()->getCurrentChannel()->id,
            'locale' => core()->getCurrentLocale()->code,
        ];
    }
}
