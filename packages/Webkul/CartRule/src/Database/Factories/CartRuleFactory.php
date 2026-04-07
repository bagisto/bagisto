<?php

namespace Webkul\CartRule\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\CartRule\Models\CartRule;

class CartRuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartRule::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'starts_from' => null,
            'ends_till' => null,
            'coupon_type' => 0,
            'use_auto_generation' => false,
            'usage_per_customer' => 100,
            'uses_per_coupon' => 100,
            'times_used' => 0,
            'condition_type' => 2,
            'action_type' => 'by_percent',
            'discount_amount' => $this->faker->numberBetween(1, 50),
            'end_other_rules' => false,
            'uses_attribute_conditions' => false,
            'discount_quantity' => 0,
            'discount_step' => 0,
            'apply_to_shipping' => false,
            'free_shipping' => false,
            'sort_order' => 0,
            'status' => true,
            'conditions' => null,
        ];
    }
}
