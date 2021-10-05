<?php

namespace Webkul\Tax\Database\Factories;

use Webkul\Tax\Models\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaxRate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'identifier' => $this->faker->uuid,
            'is_zip'     => 0,
            'zip_code'   => '*',
            'zip_from'   => null,
            'zip_to'     => null,
            'state'      => '',
            'country'    => $this->faker->countryCode,
            'tax_rate'   => $this->faker->randomFloat(2, 3, 25),
        ];
    }
}