<?php

namespace Webkul\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Wishlist;
use Webkul\Product\Models\Product;

class CustomerWishlistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wishlist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     *
     * @throws \Exception
     */
    public function definition(): array
    {
        return [
            'channel_id'  => Channel::factory(),
            'product_id'  => Product::factory(),
            'customer_id' => Customer::factory(),
        ];
    }
}
