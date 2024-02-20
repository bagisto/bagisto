<?php

namespace Webkul\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Sales\Models\InvoiceItem;

class InvoiceItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $basePrice = $this->faker->randomFloat(2);

        $quantity = $this->faker->randomNumber();

        return [
            'name'            => $this->faker->word,
            'sku'             => $this->faker->unique()->ean13,
            'qty'             => $quantity,
            'price'           => $basePrice,
            'base_price'      => $basePrice,
            'total'           => $quantity * $basePrice,
            'base_total'      => $quantity * $basePrice,
            'tax_amount'      => 0,
            'base_tax_amount' => 0,
        ];
    }
}
