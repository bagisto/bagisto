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
     * @return array
     *
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'first_name'        => $this->faker->firstName(),
            'last_name'         => $this->faker->lastName,
            'gender'            => Arr::random([
                'male',
                'female',
                'other',
            ]),
            'email'             => $this->faker->email,
            'status'            => 1,
            'password'          => Hash::make($password = $this->faker->password),
            'customer_group_id' => 2,
            'is_verified'       => 1,
            'created_at'        => $now = date('Y-m-d H:i:s'),
            'updated_at'        => $now,
            'notes'             => json_encode(['plain_password' => $password], JSON_THROW_ON_ERROR),
        ];
    }

    /**
     * Male.
     *
     * @return \Webkul\Customer\Database\Factories\CustomerFactory
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
     *
     * @return \Webkul\Customer\Database\Factories\CustomerFactory
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
