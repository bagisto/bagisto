<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleCartRepository as CartRuleCart;

class ValidatesDiscount
{
    /**
     * CartRuleCartRepository instance
     */
    protected $cartRuleCart;

    /**
     * Initializes type hinted dependencies
     *
     * @param CartRuleCart $cartRuleCart
     */
    public function __construct(CartRuleCart $cartRuleCart)
    {
        $this->cartRuleCart = $cartRuleCart;
    }

    /**
     * Validates the currently applied cart rule on the current cart
     *
     * @param $cart instance
     *
     * @return mixed
     */
    public function validate($cart)
    {
        $appliedRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($appliedRule->count()) {
            $appliedRule = $appliedRule->first()->cart_rule;

            if ($appliedRule->status == 1) {
                $applicability = $this->checkApplicability($appliedRule);

                if (! $applicability) {
                    return $this->remove();
                } else {
                    if ($appliedRule->free_shipping && $cart->selected_shipping_rate->base_price > 0) {
                        $cart->selected_shipping_rate->update([
                            'price' => 0,
                            'base_price' => 0
                        ]);
                    } else if ($appliedRule->free_shipping == 0 && $appliedRule->apply_to_shipping && $cart->selected_shipping_rate->base_price > 0) {
                        $actionType = config('discount-rules')[$appliedRule->action_type];

                        if ($appliedRule->apply_to_shipping) {
                            $actionInstance = new $actionType;

                            $discountOnShipping = $actionInstance->calculateOnShipping($cart);

                            $cart->selected_shipping_rate->update([
                                'price' => $cart->selected_shipping_rate->base_price - $discountOnShipping,
                                'base_price' => $cart->selected_shipping_rate->price - core()->convertPrice($discountOnShipping, $cart->cart_currency_code)
                            ]);
                        }
                    }
                }
            } else {
                return $this->remove();
            }
        }

        return false;
    }

    /**
     * Checks whether coupon is getting applied on current cart instance or not
     *
     * @return boolean
     */
    public function checkApplicability($rule)
    {
        $cart = \Cart::getCart();

        $timeBased = false;

        // time based constraints
        if ($rule->starts_from != null && $rule->ends_till == null) {
            if (Carbon::parse($rule->starts_from) < now()) {
                $timeBased = true;
            }
        } else if ($rule->starts_from == null && $rule->ends_till != null) {
            if (Carbon::parse($rule->ends_till) > now()) {
                $timeBased = true;
            }
        } else if ($rule->starts_from != null && $rule->ends_till != null) {
            if (Carbon::parse($rule->starts_from) < now() && now() < Carbon::parse($rule->ends_till)) {
                $timeBased = true;
            }
        } else {
            $timeBased = true;
        }

        $channelBased = false;

        // channel based constraints
        foreach ($rule->channels as $channel) {
            if ($channel->channel_id == core()->getCurrentChannel()->id) {
                $channelBased = true;
            }
        }

        $customerGroupBased = false;

        // customer groups based constraints
        if (auth()->guard('customer')->check()) {
            foreach ($rule->customer_groups as $customer_group) {
                if (auth()->guard('customer')->user()->group->exists()) {
                    if ($customer_group->customer_group_id == auth()->guard('customer')->user()->group->id) {
                        $customerGroupBased = true;
                    }
                }
            }
        } else {
            foreach ($rule->customer_groups as $customer_group) {
                if ($customer_group->customer_group->code == 'guest') {
                    $customerGroupBased = true;
                }
            }
        }

        $conditionsBased = true;

        //check conditions
        if ($rule->conditions != null) {
            $conditions = json_decode(json_decode($rule->conditions));

            $test_mode = array_last($conditions);

            if ($test_mode->criteria == 'any_is_true') {
                $conditionsBased = $this->testIfAnyConditionIsTrue($conditions, $cart);
            }

            if ($test_mode->criteria == 'all_are_true') {
                $conditionsBased = $this->testIfAllConditionAreTrue($conditions, $cart);
            }
        }

        if ($timeBased && $channelBased && $customerGroupBased && $conditionsBased) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes the already applied coupon on the current cart instance
     *
     * @return boolean
     */
    public function remove()
    {
        $cart = Cart::getCart();

        $existingRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($existingRule->count()) {
            $existingRule->first()->delete();

            foreach ($cart->items as $item) {
                if ($item->discount_amount > 0) {
                    $item->update([
                        'discount_amount' => 0,
                        'base_discount_amount' => 0,
                        'discount_percent' => 0,
                        'coupon_code' => NULL
                    ]);
                }
            }

            $cart->update([
                'coupon_code' => NULL,
                'discount_amount' => 0,
                'base_discount_amount' => 0
            ]);

            Cart::collectTotals();

            return true;
        } else {
            return false;
        }
    }

    /**
     * Checks the rule against the current cart instance whether rule conditions are applicable
     * or not
     *
     * @return boolean
     */
    protected function testIfAllConditionAreTrue($conditions, $cart)
    {
        $paymentMethods = $this->getPaymentMethods();

        $shippingMethods = $this->getShippingMethods();

        array_pop($conditions);

        $shipping_address = $cart->getShippingAddressAttribute() ?? null;

        $shipping_method = $cart->selected_shipping_rate->method_title ?? null;

        $shipping_country = $shipping_address->country ?? null;

        $shipping_state = $shipping_address->state ?? null;

        $shipping_postcode = $shipping_address->postcode ?? null;

        $shipping_city = $shipping_address->city ?? null;

        if (isset($cart->payment)) {
            $payment_method = $paymentMethods[$cart->payment->method]['title'];
        } else {
            $payment_method = null;
        }

        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;

        $total_weight = 0;

        foreach ($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $result = true;

        foreach ($conditions as $condition) {
            if (isset($condition->attribute)) {
                $actual_value = ${$condition->attribute};

            } else {
                $result = false;
            }

            if (isset($condition->value)) {
                $test_value = $condition->value;

            } else {
                $result = false;
            }

            if (isset($condition->condition)) {
                $test_condition = $condition->condition;
            }
            else {
                $result = false;
            }

            if (isset($condition->type) && ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text')) {
                if ($condition->type == 'string') {
                    $actual_value = strtolower($actual_value);

                    $test_value = strtolower($test_value);
                }

                if ($test_condition == '=') {
                    if ($actual_value != $test_value) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if (! ($actual_value >= $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if (! ($actual_value <= $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if (! ($actual_value > $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if (! ($actual_value < $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (! str_contains($actual_value, $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = false;

                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Checks the rule against the current cart instance whether rule conditions are applicable
     * or not
     *
     * @return boolean
     */
    protected function testIfAnyConditionIsTrue($conditions, $cart)
    {
        $paymentMethods = $this->getPaymentMethods();

        $shippingMethods = $this->getShippingMethods();

        array_pop($conditions);

        $result = false;

        $shipping_address = $cart->getShippingAddressAttribute() ?? null;

        $shipping_method = $cart->selected_shipping_rate->method_title ?? null;

        $shipping_country = $shipping_address->country ?? null;

        $shipping_state = $shipping_address->state ?? null;

        $shipping_postcode = $shipping_address->postcode ?? null;

        $shipping_city = $shipping_address->city ?? null;

        if (isset($cart->payment)) {
            $payment_method = $paymentMethods[$cart->payment->method]['title'];
        } else {
            $payment_method = null;
        }

        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;

        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        foreach ($conditions as $condition) {
            if (isset($condition->attribute)) {
                $actual_value = ${$condition->attribute};

            } else {
                $result = false;
            }

            if (isset($condition->value)) {
                $test_value = $condition->value;

            } else {
                $result = false;
            }

            if (isset($condition->condition)) {
                $test_condition = $condition->condition;
            }
            else {
                $result = false;
            }

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text') {
                if ($condition->type == 'string') {
                    $actual_value = strtolower($actual_value);

                    $test_value = strtolower($test_value);
                }

                if ($test_condition == '=') {
                    if ($actual_value == $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if ($actual_value >= $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if ($actual_value <= $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if ($actual_value > $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if ($actual_value < $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = true;

                        break;
                    }
                }
            }
        }

        return $result;
    }
}