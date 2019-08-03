<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class PercentOfProduct extends Action
{
    public function calculate($rule)
    {
        $cart = \Cart::getCart();
        $items = $cart->items;

        $impact = collect();
        $totalDiscount = 0;

        if ($rule->uses_attribute_conditions) {
            $productIds = $rule->product_ids;

            $productIds = explode(',', $productIds);

            foreach ($items as $item) {
                $product = $item->product;

                if ($product->type == 'configurable') {
                    $product = $item->child->product;
                }

                foreach ($productIds as $productId) {
                    $disc_threshold = $rule->disc_threshold;
                    $disc_amount = $rule->disc_amount;
                    $disc_quantity = $rule->disc_quantity;
                    $amountDiscounted = 0;
                    $report = array();

                    $realQty = $item->quantity;

                    if ($productId == $product->id && $cart->items_qty >= $disc_threshold) {
                        // distribute the discount according to ratios in case when rule is getting applied on multiple items

                        $amountDiscounted = $item->base_price * ($disc_amount / 100);

                        if ($realQty > $disc_quantity) {
                            $amountDiscounted = $amountDiscounted * $disc_quantity;
                        } else {
                            $amountDiscounted = $amountDiscounted * $realQty;
                        }

                        if ($amountDiscounted > $item->base_price && $realQty == 1) {
                            $amountDiscounted = $item->base_price;
                        }

                        $totalDiscount = $totalDiscount + $amountDiscounted;

                        $report['item_id'] = $item->id;
                        $report['product_id'] = $productId;
                        $report['discount'] = $amountDiscounted;
                        $report['formatted_discount'] = core()->currency($amountDiscounted);

                        $impact->push($report);

                        unset($report);
                    }
                }
            }
        } else {
            $productIds = null;

            foreach ($items as $item) {
                $product = $item->product;

                if ($product->type == 'configurable') {
                    $product = $item->child->product;
                }

                $disc_threshold = $rule->disc_threshold;
                $disc_amount = $rule->disc_amount;
                $disc_quantity = $rule->disc_quantity;
                $amountDiscounted = 0;
                $report = array();

                $realQty = $item->quantity;

                if ($productId == $product->id && $cart->items_qty >= $disc_threshold) {
                    // distribute the discount according to ratios in case when rule is getting applied on multiple items

                    $amountDiscounted = $item->base_price * ($disc_amount / 100);

                    if ($realQty > $disc_quantity) {
                        $amountDiscounted = $amountDiscounted * $disc_quantity;
                    } else {
                        $amountDiscounted = $amountDiscounted * $realQty;
                    }

                    if ($amountDiscounted > $item->base_price && $realQty == 1) {
                        $amountDiscounted = $item->base_price;
                    }

                    $totalDiscount = $totalDiscount + $amountDiscounted;

                    $report['item_id'] = $item->id;
                    $report['product_id'] = $productId;
                    $report['discount'] = $amountDiscounted;
                    $report['formatted_discount'] = core()->currency($amountDiscounted);

                    $impact->push($report);

                    unset($report);
                }
            }
        }

        // if (isset($rule->apply_on_shipping) && ! $rule->free_shipping) {
        //     $impact->discount_on_shipping = $this->calculateOnShipping();
        // } else {
        //     $rule->discount_on_shipping = 0;
        //     $rule->base_discount_on_shipping = 0;
        // }

        $impact->discount = $totalDiscount;
        $impact->formatted_discount = core()->currency($totalDiscount);

        return $impact;
    }

    /**
     * Calculates the impact on the shipping amount if the rule is apply_to_shipping enabled
     */
    public function calculateOnShipping()
    {
        $cart = \Cart::getCart();

        $shippingRate = $cart->selected_shipping_rate;

        $percentOfDiscount = ($cart->base_discount_amount * 100) / $cart->base_grand_total;

        return $percentOfDiscount;
    }
}