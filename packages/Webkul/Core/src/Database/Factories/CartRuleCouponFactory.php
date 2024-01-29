<?php

namespace Webkul\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Webkul\CartRule\Models\CartRuleCoupon;

class CartRuleCouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartRuleCoupon::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code'               => Str::uuid(),
            'usage_limit'        => 100,
            'usage_per_customer' => 100,
            'type'               => 0,
            'is_primary'         => 1,
        ];
    }
}
