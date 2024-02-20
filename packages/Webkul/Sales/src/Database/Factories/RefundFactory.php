<?php

namespace Webkul\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Sales\Models\Refund;

class RefundFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Refund::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
