<?php

namespace Webkul\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Models\Customer;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * States.
     *
     * @var array
     */
    protected $states = [
        'male',
        'female',
    ];

    /**
     * Define the model's default state.
     *
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName,
            'gender'            => Arr::random(['male', 'female', 'other']),
            'email'             => $this->faker->email,
            'status'            => 1,
            'password'          => Hash::make($this->faker->password),
            'customer_group_id' => 2,
            'is_verified'       => 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }

    /**
     * Male.
     */
    public function male(): CustomerFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'gender' => 'Male',
            ];
        });
    }

    /**
     * Female.
     */
    public function female(): CustomerFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'gender' => 'Female',
            ];
        });
    }
}
