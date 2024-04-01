<?php

namespace Webkul\Shop\Tests\Concerns;

use Webkul\Checkout\Contracts\Cart;
use Webkul\Checkout\Contracts\CartItem;
use Webkul\Checkout\Contracts\CartPayment;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Faker\Helpers\Customer as CustomerFaker;

trait ShopTestBench
{
    /**
     * Login as customer.
     */
    public function loginAsCustomer(?CustomerContract $customer = null): CustomerContract
    {
        $customer = $customer ?? (new CustomerFaker())->factory()->create();

        $this->actingAs($customer);

        return $customer;
    }

    /**
     * Assert the cart.
     */
    public function assertCart(Cart $cart): void
    {
        $cart->refresh();

        $this->assertDatabaseHas('cart', [
            'id'                    => $cart->id,
            'customer_email'        => $cart->customer_email,
            'customer_first_name'   => $cart->customer_first_name,
            'customer_last_name'    => $cart->customer_last_name,
            'shipping_method'       => $cart->shipping_method,
            'coupon_code'           => $cart->coupon_code,
            'is_gift'               => $cart->is_gift,
            'items_count'           => $cart->items_count,
            'items_qty'             => $cart->items_qty,
            'exchange_rate'         => $cart->exchange_rate,
            'global_currency_code'  => $cart->global_currency_code,
            'base_currency_code'    => $cart->base_currency_code,
            'channel_currency_code' => $cart->channel_currency_code,
            'cart_currency_code'    => $cart->cart_currency_code,
            'grand_total'           => $cart->grand_total,
            'base_grand_total'      => $cart->base_grand_total,
            'sub_total'             => $cart->sub_total,
            'base_sub_total'        => $cart->base_sub_total,
            'tax_total'             => $cart->tax_total,
            'base_tax_total'        => $cart->base_tax_total,
            'discount_amount'       => $cart->discount_amount,
            'base_discount_amount'  => $cart->base_discount_amount,
            'checkout_method'       => $cart->checkout_method,
            'is_guest'              => $cart->is_guest,
            'is_active'             => $cart->is_active,
            'applied_cart_rule_ids' => $cart->applied_cart_rule_ids,
            'customer_id'           => $cart->customer_id,
            'channel_id'            => $cart->channel_id,
        ]);
    }

    /**
     * Assert multiple Cart Items.
     */
    public function assertCartItems(array $cartItems): void
    {
        foreach ($cartItems as $cartItem) {
            $this->assertCartItem($cartItem);
        }
    }

    /**
     * Assert cart items.
     */
    public function assertCartItem(CartItem $cartItem): void
    {
        $cartItem->refresh();

        $this->assertDatabaseHas('cart_items', [
            'quantity'              => $cartItem->quantity,
            'sku'                   => $cartItem->sku,
            'type'                  => $cartItem->type,
            'name'                  => $cartItem->name,
            'coupon_code'           => $cartItem->coupon_code,
            'weight'                => $cartItem->weight,
            'total_weight'          => $cartItem->total_weight,
            'base_total_weight'     => $cartItem->base_total_weight,
            'price'                 => core()->convertPrice($cartItem->price),
            'base_price'            => $cartItem->base_price,
            'custom_price'          => $cartItem->custom_price,
            'total'                 => $cartItem->total,
            'base_total'            => $cartItem->base_total,
            'tax_percent'           => $cartItem->tax_percent,
            'tax_amount'            => $cartItem->tax_amount,
            'base_tax_amount'       => $cartItem->base_tax_amount,
            'discount_percent'      => $cartItem->discount_percent,
            'discount_amount'       => $cartItem->discount_amount,
            'base_discount_amount'  => $cartItem->base_discount_amount,
            'parent_id'             => $cartItem->parent_id,
            'product_id'            => $cartItem->product_id,
            'cart_id'               => $cartItem->cart_id,
            'tax_category_id'       => $cartItem->tax_category_id,
            'applied_cart_rule_ids' => $cartItem->applied_cart_rule_ids,
        ]);
    }

    /**
     * Assert cart payment.
     */
    public function assertCartPayment(CartPayment $cartPayment): void
    {
        $cartPayment->refresh();

        $this->assertDatabaseHas('cart_payment', [
            'cart_id'      => $cartPayment->cart_id,
            'method'       => $cartPayment->method,
            'method_title' => $cartPayment->method_title,
        ]);
    }

    /**
     * Assert address.
     */
    public function assertAddress(mixed $address): void
    {
        $address->refresh();

        $this->assertDatabaseHas('addresses', [
            'parent_address_id' => $address->parent_address_id,
            'customer_id'       => $address->customer_id,
            'additional'        => $address->additional,
            'address'           => $address->address,
            'address_type'      => $address->address_type,
            'cart_id'           => $address->cart_id,
            'city'              => $address->city,
            'company_name'      => $address->company_name,
            'country'           => $address->country,
            'created_at'        => $address->created_at,
            'customer_id'       => $address->customer_id,
            'default_address'   => $address->default_address,
            'email'             => $address->email,
            'first_name'        => $address->first_name,
            'gender'            => $address->gender,
            'id'                => $address->id,
            'last_name'         => $address->last_name,
            'order_id'          => $address->order_id,
            'parent_address_id' => $address->parent_address_id,
            'phone'             => $address->phone,
            'postcode'          => $address->postcode,
            'state'             => $address->state,
            'updated_at'        => $address->updated_at,
            'use_for_shipping'  => $address->use_for_shipping,
            'vat_id'            => $address->vat_id,
            'address'           => $address->address,
        ]);
    }
}
