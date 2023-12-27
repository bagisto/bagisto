<?php

namespace Webkul\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Marketing\Models\SearchSynonym;

class SearchSynonymFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SearchSynonym::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        $terms = ['jackets', 'shoes', 'footwear',  'phone', 'computers', 'electronics'];

        return [
            'terms' => $terms[array_rand($terms)],
            'name'  => $this->faker->name,
        ];
    }
}
