<?php

namespace Webkul\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Sales\Models\OrderTransaction;

class OrderTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'transaction_id' => bin2hex(random_bytes(20)),
            'type'           => 'cashondelivery',
            'payment_method' => 'cashondelivery',
            'status'         => 'paid',
        ];
    }
}
