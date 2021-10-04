<?php

namespace Webkul\Customer\Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Webkul\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
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
     * @throws \Exception
     */
    public function definition(): array
    {
        $now      = date("Y-m-d H:i:s");
        $password = $this->faker->password;

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
            'password'          => Hash::make($password),
            'customer_group_id' => 2,
            'is_verified'       => 1,
            'created_at'        => $now,
            'updated_at'        => $now,
            'notes'             => json_encode(['plain_password' => $password], JSON_THROW_ON_ERROR),
        ];
    }

    public function male(): CustomerFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'gender' => 'Male',
            ];
        });
    }

    public function female(): CustomerFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'gender' => 'Female',
            ];
        });
    }

}
