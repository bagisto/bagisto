<?php

namespace Webkul\BookingProduct\Database\Factories;

use Webkul\BookingProduct\Models\BookingProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\BookingProduct\Models\BookingProductEventTicket;

class BookingProductEventTicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookingProductEventTicket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'price'              => $this->faker->randomFloat(4, 3, 900),
            'qty'                => $this->faker->numberBetween(100, 1000),
            'booking_product_id' => BookingProduct::factory(),
        ];
    }
}