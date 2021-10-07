<?php

namespace Webkul\Sales\Database\Factories;

use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     *
     * @return array
     */
    public function definition(): array
    {
        $subTotal = $this->faker->randomFloat(2);
        $shippingAmount = $this->faker->randomFloat(2);
        $taxAmount = $this->faker->randomFloat(2);

        if (!isset($attributes['order_id'])) {
            $attributes['order_id'] = Order::factory();
        }

        if (!isset($attributes['order_address_id'])) {
            $attributes['order_address_id'] = OrderAddress::factory();
        }

        return [
            'email_sent' => 0,
            'total_qty' => $this->faker->randomNumber(),
            'base_currency_code' => 'EUR',
            'channel_currency_code' => 'EUR',
            'order_currency_code' => 'EUR',
            'sub_total' => $subTotal,
            'base_sub_total' => $subTotal,
            'grand_total' => $subTotal,
            'base_grand_total' => $subTotal,
            'shipping_amount' => $shippingAmount,
            'base_shipping_amount' => $shippingAmount,
            'tax_amount' => $taxAmount,
            'base_tax_amount' => $taxAmount,
            'discount_amount' => 0,
            'base_discount_amount' => 0,
            'order_id' => $attributes['order_id'],
            'order_address_id' => $attributes['order_address_id'],
        ];
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