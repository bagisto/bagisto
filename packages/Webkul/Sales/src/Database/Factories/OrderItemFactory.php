<?php

namespace Webkul\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\OrderItem;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $fallbackPrice = $this->faker->randomFloat(4, 0, 1000);

        return [
            'price'        => $fallbackPrice,
            'base_price'   => $fallbackPrice,
            'total'        => $fallbackPrice,
            'base_total'   => $fallbackPrice,
            'qty_ordered'  => 1,
            'qty_shipped'  => 0,
            'qty_invoiced' => 0,
            'qty_canceled' => 0,
            'qty_refunded' => 0,
            'additional'   => [],
            'created_at'   => now(),
            'updated_at'   => now(),
            'product_type' => Product::class,
        ];
    }
}
