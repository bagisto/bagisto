<?php

namespace Webkul\Core\Tests\Concerns;

use Webkul\CartRule\Contracts\CartRule;
use Webkul\CartRule\Contracts\CartRuleCoupon;
use Webkul\CatalogRule\Contracts\CatalogRule;
use Webkul\Checkout\Contracts\Cart;
use Webkul\Checkout\Contracts\CartItem;
use Webkul\Checkout\Contracts\CartPayment;
use Webkul\Checkout\Contracts\CartShippingRate;
use Webkul\Sales\Contracts\Invoice;
use Webkul\Sales\Contracts\InvoiceItem;
use Webkul\Sales\Contracts\OrderItem;
use Webkul\Sales\Contracts\OrderPayment;
use Webkul\Sales\Contracts\OrderTransaction;
use Webkul\Sales\Models\Order;

trait CoreAssertions
{
    /**
     * Assert model wise.
     */
    public function assertModelWise(array $modelWiseAssertions): void
    {
        foreach ($modelWiseAssertions as $modelClassName => $modelAssertions) {
            foreach ($modelAssertions as $assertion) {
                $this->assertDatabaseHas(app($modelClassName)->getTable(), $assertion);
            }
        }
    }

    /**
     * Assert that two numbers are equal with optional decimal precision.
     */
    public function assertPrice(float $expected, float $actual, ?int $decimal = null): void
    {
        $decimal = $decimal ?? core()->getCurrentChannel()->decimal;

        $expectedFormatted = number_format($expected, $decimal);

        $actualFormatted = number_format($actual, $decimal);

        $this->assertEquals($expectedFormatted, $actualFormatted);
    }

    /**
     * Prepare order for assertion.
     */
    public function prepareOrder(Order $order): array
    {
        return [
            'status'                        => $order->status,
            'channel_name'                  => $order->channel_name,
            'is_guest'                      => $order->is_guest,
            'customer_email'                => $order->customer_email,
            'customer_first_name'           => $order->customer_first_name,
            'customer_last_name'            => $order->customer_last_name,
            'shipping_method'               => $order->shipping_method,
            'shipping_title'                => $order->shipping_title,
            'shipping_description'          => $order->shipping_description,
            'coupon_code'                   => $order->coupon_code,
            'is_gift'                       => $order->is_gift,
            'total_item_count'              => $order->total_item_count,
            'total_qty_ordered'             => $order->total_qty_ordered,
            'base_currency_code'            => $order->base_currency_code,
            'channel_currency_code'         => $order->channel_currency_code,
            'order_currency_code'           => $order->order_currency_code,
            'grand_total'                   => $order->grand_total,
            'base_grand_total'              => $order->base_grand_total,
            'grand_total_invoiced'          => $order->grand_total_invoiced,
            'base_grand_total_invoiced'     => $order->base_grand_total_invoiced,
            'grand_total_refunded'          => $order->grand_total_refunded,
            'base_grand_total_refunded'     => $order->base_grand_total_refunded,
            'sub_total'                     => $order->sub_total,
            'base_sub_total'                => $order->base_sub_total,
            'sub_total_invoiced'            => $order->sub_total_invoiced,
            'grand_total_refunded'          => $order->grand_total_refunded,
            'base_grand_total_refunded'     => $order->base_grand_total_refunded,
            'sub_total'                     => $order->sub_total,
            'base_sub_total'                => $order->base_sub_total,
            'sub_total_invoiced'            => $order->sub_total_invoiced,
            'base_sub_total_invoiced'       => $order->base_sub_total_invoiced,
            'sub_total_refunded'            => $order->sub_total_refunded,
            'discount_percent'              => $order->discount_percent,
            'discount_amount'               => $order->discount_amount,
            'base_discount_amount'          => $order->base_discount_amount,
            'discount_invoiced'             => $order->discount_invoiced,
            'base_discount_invoiced'        => $order->base_discount_invoiced,
            'discount_refunded'             => $order->discount_refunded,
            'base_discount_refunded'        => $order->base_discount_refunded,
            'tax_amount'                    => $order->tax_amount,
            'base_tax_amount'               => $order->base_tax_amount,
            'tax_amount_invoiced'           => $order->tax_amount_invoiced,
            'base_tax_amount_invoiced'      => $order->base_tax_amount_invoiced,
            'tax_amount_refunded'           => $order->tax_amount_refunded,
            'base_tax_amount_refunded'      => $order->base_tax_amount_refunded,
            'shipping_amount'               => $order->shipping_amount,
            'base_shipping_amount'          => $order->base_shipping_amount,
            'shipping_invoiced'             => $order->shipping_invoiced,
            'base_shipping_invoiced'        => $order->base_shipping_invoiced,
            'shipping_refunded'             => $order->shipping_refunded,
            'base_shipping_refunded'        => $order->base_shipping_refunded,
            'shipping_discount_amount'      => $order->shipping_discount_amount,
            'base_shipping_discount_amount' => $order->base_shipping_discount_amount,
            'customer_id'                   => $order->customer_id,
            'customer_type'                 => $order->customer_type,
            'channel_id'                    => $order->channel_id,
            'channel_type'                  => $order->channel_type,
            'cart_id'                       => $order->cart_id,
            'applied_cart_rule_ids'         => $order->applied_cart_rule_ids,
        ];
    }

    /**
     * Prepare order using cart for assertion.
     */
    public function prepareOrderUsingCart(Cart $cart): array
    {
        return [
            'cart_id'               => $cart->id,
            'customer_id'           => $cart->customer_id,
            'is_guest'              => $cart->is_guest,
            'customer_email'        => $cart->customer_email,
            'customer_first_name'   => $cart->customer_first_name,
            'customer_last_name'    => $cart->customer_last_name,
            'total_item_count'      => $cart->items_count,
            'total_qty_ordered'     => $cart->items_qty,
            'base_currency_code'    => $cart->base_currency_code,
            'channel_currency_code' => $cart->channel_currency_code,
            'order_currency_code'   => $cart->cart_currency_code,
            'grand_total'           => $cart->grand_total,
            'base_grand_total'      => $cart->base_grand_total,
            'sub_total'             => $cart->sub_total,
            'base_sub_total'        => $cart->base_sub_total,
            'tax_amount'            => $cart->tax_total,
            'base_tax_amount'       => $cart->base_tax_total,
            'coupon_code'           => $cart->coupon_code,
            'applied_cart_rule_ids' => $cart->applied_cart_rule_ids,
            'discount_amount'       => $cart->discount_amount,
            'base_discount_amount'  => $cart->base_discount_amount,
        ];
    }

    /**
     * Prepare Order Item for assertion.
     */
    public function prepareOrderItem(OrderItem $orderItem): array
    {
        return [
            'id'                     => $orderItem->id,
            'sku'                    => $orderItem->sku,
            'type'                   => $orderItem->type,
            'name'                   => $orderItem->name,
            'coupon_code'            => $orderItem->coupon_code,
            'weight'                 => $orderItem->weight,
            'total_weight'           => $orderItem->total_weight,
            'qty_ordered'            => $orderItem->qty_ordered,
            'qty_shipped'            => $orderItem->qty_shipped,
            'qty_invoiced'           => $orderItem->qty_invoiced,
            'qty_canceled'           => $orderItem->qty_canceled,
            'qty_refunded'           => $orderItem->qty_refunded,
            'price'                  => $orderItem->price,
            'base_price'             => $orderItem->base_price,
            'total'                  => $orderItem->total,
            'base_total'             => $orderItem->base_total,
            'total_invoiced'         => $orderItem->total_invoiced,
            'base_total_invoiced'    => $orderItem->base_total_invoiced,
            'amount_refunded'        => $orderItem->amount_refunded,
            'base_amount_refunded'   => $orderItem->base_amount_refunded,
            'discount_percent'       => $orderItem->discount_percent,
            'discount_amount'        => $orderItem->discount_amount,
            'base_discount_amount'   => $orderItem->base_discount_amount,
            'discount_invoiced'      => $orderItem->discount_invoiced,
            'base_discount_invoiced' => $orderItem->base_discount_invoiced,
        ];
    }

    /**
     * Prepare order items for assertion.
     */
    public function prepareOrderItemUsingCartItem(CartItem $cartItem)
    {
        return [
            'product_id'           => $cartItem->product_id,
            'sku'                  => $cartItem->sku,
            'type'                 => $cartItem->type,
            'name'                 => $cartItem->name,
            'weight'               => $cartItem->weight,
            'total_weight'         => $cartItem->total_weight,
            'qty_ordered'          => $cartItem->quantity,
            'price'                => $cartItem->price,
            'base_price'           => $cartItem->base_price,
            'total'                => $cartItem->total,
            'base_total'           => $cartItem->base_total,
            'tax_percent'          => $cartItem->tax_percent,
            'tax_amount'           => $cartItem->tax_amount,
            'base_tax_amount'      => $cartItem->base_tax_amount,
            'tax_category_id'      => $cartItem->tax_category_id,
            'discount_percent'     => $cartItem->discount_percent,
            'discount_amount'      => $cartItem->discount_amount,
            'base_discount_amount' => $cartItem->base_discount_amount,
        ];
    }

    /**
     * Prepare Order Payment for Assertion.
     */
    public function prepareOrderPaymentUsingCartPayment(CartPayment $cartPayment): array
    {
        return [
            'method'       => $cartPayment->method,
            'method_title' => $cartPayment->method_title,
        ];
    }

    public function prepareOrderTransaction(OrderTransaction $orderTransaction): array
    {
        return [
            'order_id'       => $orderTransaction->order_id,
            'invoice_id'     => $orderTransaction->invoice_id,
            'transaction_id' => $orderTransaction->transaction_id,
            'type'           => $orderTransaction->type,
            'payment_method' => $orderTransaction->payment_method,
            'status'         => $orderTransaction->status,
            'amount'         => $orderTransaction->amount,
        ];
    }

    /**
     * Prepare the cart for assertion.
     */
    public function prepareCart(Cart $cart): array
    {
        return [
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
        ];
    }

    /**
     * Prepare cart item for assertion.
     */
    public function prepareCartItem(CartItem $cartItem): array
    {
        return [
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
            'base_discount_amount'  => $cartItem->base_discount_amount,
            'tax_percent'           => number_format($cartItem->tax_percent, 4),
            'tax_amount'            => number_format($cartItem->tax_amount, 4),
            'base_tax_amount'       => number_format($cartItem->base_tax_amount, 4),
            'discount_amount'       => number_format($cartItem->discount_amount, 4),
            'base_discount_amount'  => number_format($cartItem->base_discount_amount, 4),
            'parent_id'             => $cartItem->parent_id,
            'product_id'            => $cartItem->product_id,
            'cart_id'               => $cartItem->cart_id,
            'tax_category_id'       => $cartItem->tax_category_id,
            'applied_cart_rule_ids' => $cartItem->applied_cart_rule_ids,
        ];
    }

    /**
     * Prepare cart payment for assertion.
     */
    public function prepareCartPayment(CartPayment $cartPayment): array
    {
        return [
            'cart_id'      => $cartPayment->cart_id,
            'method'       => $cartPayment->method,
            'method_title' => $cartPayment->method_title,
        ];
    }

    /**
     * Prepare cart shipping rate for assertion.
     */
    public function prepareCartShippingRate(CartShippingRate $cartShippingRate): array
    {
        return [
            'carrier'            => $cartShippingRate->carrier,
            'carrier_title'      => $cartShippingRate->carrier_title,
            'method'             => $cartShippingRate->method,
            'method_title'       => $cartShippingRate->method_title,
            'method_description' => $cartShippingRate->method_description,
            'cart_address_id'    => $cartShippingRate->cart_address_id,
        ];
    }

    /**
     * Prepare address for assertion.
     */
    public function prepareAddress(mixed $address, ?string $type = null): array
    {
        return [
            'additional'        => $address->additional,
            'address'           => $address->address,
            'address_type'      => $type ?? $address->address_type,
            'city'              => $address->city,
            'company_name'      => $address->company_name,
            'country'           => $address->country,
            'default_address'   => $address->default_address,
            'email'             => $address->email,
            'first_name'        => $address->first_name,
            'gender'            => $address->gender,
            'last_name'         => $address->last_name,
            'phone'             => $address->phone,
            'postcode'          => $address->postcode,
            'state'             => $address->state,
            'vat_id'            => $address->vat_id,
            'customer_id'       => ! $type ? $address->customer_id : null,
        ];
    }

    /**
     * Assert order payment for assertion.
     */
    public function prepareOrderPayment(OrderPayment $orderPayment): array
    {
        return [
            'order_id'     => $orderPayment->order_id,
            'method'       => $orderPayment->method,
            'method_title' => $orderPayment->method_title,
        ];
    }

    /**
     * Prepare invoice for assertion.
     */
    public function prepareInvoice(Order $order, OrderItem $orderItem): array
    {
        return [
            'order_id'              => $order->id,
            'state'                 => 'paid',
            'total_qty'             => 1,
            'base_currency_code'    => $order->base_currency_code,
            'channel_currency_code' => $order->channel_currency_code,
            'order_currency_code'   => $order->order_currency_code,
            'email_sent'            => 1,
            'discount_amount'       => 0,
            'base_discount_amount'  => 0,
            'sub_total'             => $orderItem->base_price,
            'base_sub_total'        => $orderItem->base_price,
            'grand_total'           => $orderItem->price,
            'base_grand_total'      => $orderItem->price,
        ];
    }

    /**
     * Assert invoice item for assertion.
     */
    public function prepareInvoiceItem(InvoiceItem $invoiceItem): array
    {
        return [
            'id'                   => $invoiceItem->id,
            'parent_id'            => $invoiceItem->parent_id,
            'name'                 => $invoiceItem->name,
            'description'          => $invoiceItem->description,
            'sku'                  => $invoiceItem->sku,
            'qty'                  => $invoiceItem->qty,
            'price'                => $invoiceItem->price,
            'base_price'           => $invoiceItem->base_price,
            'total'                => $invoiceItem->total,
            'base_total'           => $invoiceItem->base_total,
            'tax_amount'           => $invoiceItem->tax_amount,
            'base_tax_amount'      => $invoiceItem->base_tax_amount,
            'discount_percent'     => $invoiceItem->discount_percent,
            'discount_amount'      => $invoiceItem->discount_amount,
            'base_discount_amount' => $invoiceItem->base_discount_amount,
            'product_id'           => $invoiceItem->product_id,
            'product_type'         => $invoiceItem->product_type,
            'order_item_id'        => $invoiceItem->order_item_id,
            'invoice_id'           => $invoiceItem->invoice_id,
        ];
    }

    /**
     * Assert Cart Rule for assertion.
     */
    public function prepareCartRule(CartRule $cartRule): array
    {
        return [
            'id'                        => $cartRule->id,
            'name'                      => $cartRule->name,
            'description'               => $cartRule->description,
            'starts_from'               => $cartRule->starts_from,
            'ends_till'                 => $cartRule->ends_till,
            'status'                    => $cartRule->status,
            'coupon_type'               => $cartRule->coupon_type,
            'use_auto_generation'       => $cartRule->use_auto_generation,
            'usage_per_customer'        => $cartRule->usage_per_customer,
            'uses_per_coupon'           => $cartRule->uses_per_coupon,
            'times_used'                => $cartRule->times_used,
            'condition_type'            => $cartRule->condition_type,
            'end_other_rules'           => $cartRule->end_other_rules,
            'uses_attribute_conditions' => $cartRule->uses_attribute_conditions,
            'action_type'               => $cartRule->action_type,
            'discount_amount'           => $cartRule->discount_amount,
            'discount_quantity'         => $cartRule->discount_quantity,
            'discount_step'             => $cartRule->discount_step,
            'apply_to_shipping'         => $cartRule->apply_to_shipping,
            'free_shipping'             => $cartRule->free_shipping,
            'sort_order'                => $cartRule->sort_order,
        ];
    }

    /**
     * Assert cart rule customer group for assertion.
     */
    public function prepareCartRuleCustomerGroup(CartRule $cartRule): void
    {
        foreach ($cartRule->cart_rule_customer_groups as $cartRuleCustomerGroup) {
            $this->assertDatabaseHas('cart_rule_customer_groups', [
                'cart_rule_id'      => $cartRule->id,
                'customer_group_id' => $cartRuleCustomerGroup->id,
            ]);
        }
    }

    /**
     * Assert cart rule for assertion.
     */
    public function prepareCartRuleChannel(CartRule $cartRule): void
    {
        foreach ($cartRule->cart_rule_channels as $cartRuleChannel) {
            $this->assertDatabaseHas('cart_rule_channels', [
                'cart_rule_id' => $cartRule->id,
                'channel_id'   => $cartRuleChannel->id,
            ]);
        }
    }

    /**
     * Assert Cart Rule Coupon for assertion.
     */
    public function prepareCartRuleCoupon(CartRuleCoupon $cartRuleCoupon): array
    {
        return [
            'code'               => $cartRuleCoupon->code,
            'usage_limit'        => $cartRuleCoupon->usage_limit,
            'usage_per_customer' => $cartRuleCoupon->usage_per_customer,
            'times_used'         => $cartRuleCoupon->times_used,
            'type'               => $cartRuleCoupon->type,
            'is_primary'         => $cartRuleCoupon->is_primary,
            'expired_at'         => $cartRuleCoupon->expired_at,
        ];
    }

    /**
     * Assert cart rule coupon for assertion.
     */
    public function prepareCatalogRule(CatalogRule $catalogRule): array
    {
        return [
            'name'            => $catalogRule->name,
            'description'     => $catalogRule->description,
            'starts_from'     => $catalogRule->starts_from,
            'ends_till'       => $catalogRule->ends_till,
            'status'          => $catalogRule->status,
            'condition_type'  => $catalogRule->condition_type,
            'conditions'      => $catalogRule->conditions,
            'end_other_rules' => $catalogRule->end_other_rules,
            'action_type'     => $catalogRule->action_type,
            'discount_amount' => $catalogRule->discount_amount,
            'sort_order'      => $catalogRule->sort_order,
        ];
    }

    /**
     * Assert Catalog Rule Coupon for assertion.
     */
    public function prepareCatalogRuleCoupon(CatalogRule $catalogRule): array
    {
        return [
            'name'            => $catalogRule->name,
            'description'     => $catalogRule->description,
            'starts_from'     => $catalogRule->starts_from,
            'ends_till'       => $catalogRule->ends_till,
            'status'          => $catalogRule->status,
            'condition_type'  => $catalogRule->condition_type,
            'conditions'      => $catalogRule->conditions,
            'end_other_rules' => $catalogRule->end_other_rules,
            'action_type'     => $catalogRule->action_type,
            'discount_amount' => $catalogRule->discount_amount,
            'sort_order'      => $catalogRule->sort_order,
        ];
    }

    /**
     * Assert Catalog Rule Channel for assertion.
     */
    public function prepareCatalogRuleChannel(CatalogRule $catalogRule): void
    {
        foreach ($catalogRule->channels as $catalogRuleChannel) {
            $this->assertDatabaseHas('catalog_rule_channels', [
                'catalog_rule_id' => $catalogRule->id,
                'channel_id'      => $catalogRuleChannel->id,
            ]);
        }
    }

    /**
     * Assert Catalog Rule Customer Group for assertion.
     */
    public function prepareCatalogRuleCustomerGroup(CatalogRule $catalogRule): void
    {
        foreach ($catalogRule->customer_groups as $customerGroup) {
            $this->assertDatabaseHas('catalog_rule_customer_groups', [
                'catalog_rule_id'   => $catalogRule->id,
                'customer_group_id' => $customerGroup->id,
            ]);
        }
    }
}
