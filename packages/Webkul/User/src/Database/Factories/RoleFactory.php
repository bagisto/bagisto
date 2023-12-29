<?php

namespace Webkul\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\User\Models\Role;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'            => $this->faker->name,
            'permission_type' => $this->faker->randomElement(['custom', 'all']),
        ];
    }
}
