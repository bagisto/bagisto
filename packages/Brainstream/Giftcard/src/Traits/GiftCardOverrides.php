<?php

namespace Brainstream\Giftcard\Traits;

use Brainstream\Giftcard\Models\GiftCardBalance;
use Brainstream\Giftcard\Models\GiftCard;
use Illuminate\Support\Facades\Event;
use Webkul\Tax\Helpers\Tax;
use Illuminate\Support\Arr;

trait GiftCardOverrides
{
    public function setGiftCardCode($giftcard)
    {
        $cart = $this->getCart();
        $cart->giftcard_number = $giftcard->giftcard_number;
        $cart->giftcard_amount = $giftcard->giftcard_amount;

        $giftCardBalance = GiftCardBalance::where('giftcard_number', $giftcard->giftcard_number)->first();

        if ($giftCardBalance) {
            if ($cart->grand_total <= $giftcard->giftcard_amount) {
                $cart->giftcard_amount = $cart->sub_total;
                $cart->remaining_giftcard_amount = $giftcard->giftcard_amount - $cart->giftcard_amount;
                $giftCardBalance->used_giftcard_amount += $cart->grand_total;
                $giftCardBalance->remaining_giftcard_amount = $giftcard->giftcard_amount - $cart->grand_total;
                $cart->grand_total = 0;
            } else {
                $giftCardBalance->used_giftcard_amount += $giftcard->giftcard_amount;
                $giftCardBalance->remaining_giftcard_amount = 0;
                $cart->grand_total -= $giftcard->giftcard_amount;
            }

            $giftCardBalance->save();
        }

        $cart->save();

        return $this;
    }

    public function removeGiftCardCode()
    {
        $cart = $this->getCart();

        $giftCardNumber = $cart->giftcard_number;

        $cart->giftcard_number = null;
        $cart->giftcard_amount = null;
        $cart->remaining_giftcard_amount = null;

        $cart->save();

        if ($giftCardNumber) {
            $giftCardBalance = GiftCardBalance::where('giftcard_number', $giftCardNumber)->first();
            $giftCard = GiftCard::where('giftcard_number', $giftCardNumber)->first();

            if ($giftCardBalance && $giftCard) {
                $giftCardBalance->used_giftcard_amount = 0;
                $giftCardBalance->remaining_giftcard_amount = $giftCardBalance->giftcard_amount;

                $giftCardBalance->save();

                // Update the gift card table with the reverted amount
                $giftCard->giftcard_amount = $giftCardBalance->remaining_giftcard_amount;

                // Save the gift card
                $giftCard->save();
            }
        }

        return $this;
    }

    public function collectTotals(): void
    {
        if (! $this->validateItems()) {
            $this->resetCart();
        }

        if (! $cart = $this->getCart()) {
            return;
        }

        Event::dispatch('checkout.cart.collect.totals.before', $cart);

        $this->calculateItemsTax();

        $cart->refresh();

        $cart->sub_total = $cart->base_sub_total = 0;
        $cart->grand_total = $cart->base_grand_total = 0;
        $cart->tax_total = $cart->base_tax_total = 0;
        $cart->discount_amount = $cart->base_discount_amount = 0;

        $quantities = 0;

        foreach ($cart->items as $item) {
            $cart->discount_amount += $item->discount_amount;
            $cart->base_discount_amount += $item->base_discount_amount;
            $cart->sub_total = (float) $cart->sub_total + $item->total;
            $cart->base_sub_total = (float) $cart->base_sub_total + $item->base_total;

            $quantities += $item->quantity;
        }
        $cart->items_qty = $quantities;

        $cart->items_count = $cart->items->count();

        $cart->tax_total = Tax::getTaxTotal($cart, false);
        $cart->base_tax_total = Tax::getTaxTotal($cart, true);

        // Include gift card amount in total calculations
        $cart->grand_total = $cart->sub_total + $cart->tax_total - $cart->discount_amount - $cart->giftcard_amount;
        $cart->base_grand_total = $cart->base_sub_total + $cart->base_tax_total - $cart->base_discount_amount - $cart->giftcard_amount;

        if ($shipping = $cart->selected_shipping_rate) {
            $cart->grand_total = (float) $cart->grand_total + $shipping->price - $shipping->discount_amount;
            $cart->base_grand_total = (float) $cart->base_grand_total + $shipping->base_price - $shipping->base_discount_amount;

            $cart->discount_amount += $shipping->discount_amount;
            $cart->base_discount_amount += $shipping->base_discount_amount;
        }

        $cart->discount_amount = round($cart->discount_amount, 2);
        $cart->base_discount_amount = round($cart->base_discount_amount, 2);

        $cart->sub_total = round($cart->sub_total, 2);
        $cart->base_sub_total = round($cart->base_sub_total, 2);

        $cart->grand_total = round($cart->grand_total, 2);
        $cart->base_grand_total = round($cart->base_grand_total, 2);

        $cart->cart_currency_code = core()->getCurrentCurrencyCode();

        $cart->save();

        Event::dispatch('checkout.cart.collect.totals.after', $cart);
    }

    public function prepareDataForOrder(): array
    {
        $data = $this->toArray();

        $finalData = [
            'cart_id'               => $this->getCart()->id,
            'customer_id'           => $data['customer_id'],
            'is_guest'              => $data['is_guest'],
            'customer_email'        => $data['customer_email'],
            'customer_first_name'   => $data['customer_first_name'],
            'customer_last_name'    => $data['customer_last_name'],
            'customer'              => auth()->guard()->check() ? auth()->guard()->user() : null,
            'total_item_count'      => $data['items_count'],
            'total_qty_ordered'     => $data['items_qty'],
            'base_currency_code'    => $data['base_currency_code'],
            'channel_currency_code' => $data['channel_currency_code'],
            'order_currency_code'   => $data['cart_currency_code'],
            'grand_total'           => $data['grand_total'],
            'base_grand_total'      => $data['base_grand_total'],
            'sub_total'             => $data['sub_total'],
            'base_sub_total'        => $data['base_sub_total'],
            'tax_amount'            => $data['tax_total'],
            'base_tax_amount'       => $data['base_tax_total'],
            'coupon_code'           => $data['coupon_code'],
            'giftcard_amount'       => $data['giftcard_amount'],
            'giftcard_number'       => $data['giftcard_number'],
            'applied_cart_rule_ids' => $data['applied_cart_rule_ids'],
            'discount_amount'       => $data['discount_amount'],
            'base_discount_amount'  => $data['base_discount_amount'],
            'billing_address'       => Arr::except($data['billing_address'], ['id', 'cart_id']),
            'payment'               => Arr::except($data['payment'], ['id', 'cart_id']),
            'channel'               => core()->getCurrentChannel(),
        ];

        if ($this->getCart()->haveStockableItems()) {
            $finalData = array_merge($finalData, [
                'shipping_method'               => $data['selected_shipping_rate']['method'],
                'shipping_title'                => $data['selected_shipping_rate']['carrier_title'].' - '.$data['selected_shipping_rate']['method_title'],
                'shipping_description'          => $data['selected_shipping_rate']['method_description'],
                'shipping_amount'               => $data['selected_shipping_rate']['price'],
                'base_shipping_amount'          => $data['selected_shipping_rate']['base_price'],
                'shipping_address'              => Arr::except($data['shipping_address'], ['id', 'cart_id']),
                'shipping_discount_amount'      => $data['selected_shipping_rate']['discount_amount'],
                'base_shipping_discount_amount' => $data['selected_shipping_rate']['base_discount_amount'],
            ]);
        }

        foreach ($data['items'] as $item) {
            $finalData['items'][] = $this->prepareDataForOrderItem($item);
        }

        if ($finalData['payment']['method'] === 'paypal_smart_button') {
            $finalData['payment']['additional'] = request()->get('orderData');
        }

        return $finalData;
    }
}
