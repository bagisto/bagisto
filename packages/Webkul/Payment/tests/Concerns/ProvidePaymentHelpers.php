<?php

namespace Webkul\Payment\Tests\Concerns;

use Webkul\Checkout\Facades\Cart as CartFacade;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartPayment;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Product as ProductFaker;

trait ProvidePaymentHelpers
{
    /**
     * Create cart with items.
     */
    public function createCartWithItems(string $paymentMethod, array $overrides = []): Cart
    {
        $product = (new ProductFaker([
            'attributes' => [
                5 => 'new',
            ],
            'attribute_value' => [
                'new' => [
                    'boolean_value' => true,
                ],
            ],
        ]))
            ->getSimpleProductFactory()
            ->create();

        $customer = Customer::factory()->create();

        $cart = Cart::factory()->create(array_merge([
            'customer_id'         => $customer->id,
            'customer_first_name' => $customer->first_name,
            'customer_last_name'  => $customer->last_name,
            'customer_email'      => $customer->email,
            'is_guest'            => 0,
            'shipping_method'     => 'free_free',
        ], $overrides));

        $additional = [
            'product_id' => $product->id,
            'rating'     => '0',
            'is_buy_now' => '0',
            'quantity'   => '1',
        ];

        CartItem::factory()->create([
            'cart_id'             => $cart->id,
            'product_id'          => $product->id,
            'sku'                 => $product->sku,
            'quantity'            => $additional['quantity'],
            'name'                => $product->name,
            'price'               => $convertedPrice = core()->convertPrice($price = $product->price),
            'price_incl_tax'      => $convertedPrice,
            'base_price'          => $price,
            'base_price_incl_tax' => $price,
            'total'               => $total = $convertedPrice * $additional['quantity'],
            'total_incl_tax'      => $total,
            'base_total'          => $price * $additional['quantity'],
            'weight'              => $product->weight ?? 0,
            'total_weight'        => ($product->weight ?? 0) * $additional['quantity'],
            'base_total_weight'   => ($product->weight ?? 0) * $additional['quantity'],
            'type'                => $product->type,
            'additional'          => $additional,
        ]);

        $cartBillingAddress = CartAddress::factory()->create([
            'cart_id'      => $cart->id,
            'customer_id'  => $customer->id,
            'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        ]);

        $cartShippingAddress = CartAddress::factory()->create([
            'cart_id'      => $cart->id,
            'customer_id'  => $customer->id,
            'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
        ]);

        CartPayment::factory()->create([
            'cart_id'      => $cart->id,
            'method'       => $paymentMethod,
            'method_title' => ucfirst($paymentMethod),
        ]);

        CartShippingRate::factory()->create([
            'carrier'            => 'free',
            'carrier_title'      => 'Free shipping',
            'method'             => 'free_free',
            'method_title'       => 'Free Shipping',
            'method_description' => 'Free Shipping',
            'cart_address_id'    => $cartShippingAddress->id,
            'cart_id'            => $cart->id,
        ]);

        CartFacade::setCart($cart);

        CartFacade::collectTotals();

        return $cart->fresh();
    }
}
