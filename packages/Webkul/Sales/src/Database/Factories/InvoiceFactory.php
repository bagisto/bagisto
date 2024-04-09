<?php

namespace Webkul\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Sales\Models\Invoice;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * @var array
     */
    protected $states = [
        'pending',
        'paid',
        'refunded',
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }

    public function pending(): InvoiceFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    public function paid(): InvoiceFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'paid',
            ];
        });
    }

    public function refunded(): InvoiceFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'refunded',
            ];
        });
    }
}
