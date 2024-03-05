<?php

namespace Webkul\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Customer\Models\CustomerGroup;

class CustomerGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerGroup::class;

    /**
     * Define the model's default state.
     *
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'name'            => ucfirst($this->faker->word),
            'is_user_defined' => $this->faker->boolean,
            'code'            => $this->faker->regexify('/^[a-zA-Z]+[a-zA-Z0-9_]+$/'),
        ];
    }
}
