<?php

namespace Webkul\Marketplace\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Webkul\Customer\Models\Customer;
use Webkul\Marketplace\Models\Seller;

class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        $shopTitle = fake()->company();

        return [
            'customer_id'           => Customer::factory(),
            'shop_title'            => $shopTitle,
            'url'                   => Str::slug($shopTitle) . '-' . Str::random(5),
            'description'           => fake()->paragraph(),
            'commission_percentage' => null,
            'is_approved'           => true,
            'status'                => true,
            'phone'                 => fake()->phoneNumber(),
            'address1'              => fake()->streetAddress(),
            'city'                  => fake()->city(),
            'state'                 => fake()->state(),
            'country'               => fake()->countryCode(),
            'postcode'              => fake()->postcode(),
        ];
    }

    /**
     * Indicate the seller is unapproved.
     */
    public function unapproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    /**
     * Indicate the seller is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
