<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Discount\Repositories\CartRuleCartRepository as CartRuleCart;
use Carbon\Carbon;
use Cart;

abstract class Discount
{
    /**
     * To hold the cart rule repository instance
     */
    protected $cartRule;

    /**
     * To hold the cart rule cart repository instance
     */
    protected $cartRuleCart;

    /**
     * To hold if end rules are present or not.
     */
    protected $endRuleActive = false;

    /**
     * To hold the rule classes
     */
    protected $rules;

    public function __construct(CartRule $cartRule, CartRuleCart $cartRuleCart)
    {
        $this->cartRule = $cartRule;

        $this->cartRuleCart = $cartRuleCart;

        $this->rules = config('discount-rules');
    }

    /**
     * Abstract method apply
     */
    abstract public function apply($code);

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
     * Save the rule in the CartRule for current cart instance
     *
     * @return boolean
     */
    public function save($rule)
    {
        $cart = \Cart::getCart();

        // create or update
        $existingRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if (count($existingRule)) {
            if ($existingRule->first()->cart_rule_id != $rule->id) {
                $existingRule->first()->update([
                    'cart_rule_id' => $rule->id
                ]);

                $this->clearDiscount();

                $this->updateCartItemAndCart($rule);

                return true;
            }
        } else {

            $this->cartRuleCart->create([
                'cart_id' => $cart->id,
                'cart_rule_id' => $rule->id
            ]);

            $this->clearDiscount();

            $this->updateCartItemAndCart($rule);

            return true;
        }

        return false;
    }

    /**
     * Removes any cart rule from the current cart instance
     *
     * @return void
     */
    public function clearDiscount()
    {
        $cart = Cart::getCart();

        $cartItems = $cart->items;

        foreach($cartItems as $item) {
            $item->update([
                'coupon_code' => NULL,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'base_discount_amount' => 0
            ]);
        }

        $cart->update([
            'coupon_code' => NULL,
            'discount_amount' => 0,
            'base_discount_amount' => 0
        ]);

        return true;
    }

    /**
     * Update discount for least worth item
     */
    public function updateCartItemAndCart($rule)
    {
        $cart = Cart::getCart();

        $leastWorthItem = $this->leastWorthItem();

        $actionInstance = new $this->rules[$rule->action_type];

        $impact = $actionInstance->calculate($rule, $leastWorthItem, $cart);

        foreach ($cart->items as $item) {
            if ($item->id == $leastWorthItem['id']) {
                if ($rule->action_type == 'percent_of_product') {
                    $item->update([
                        'discount_percent' => $rule->discount_amount,
                        'discount_amount' => core()->currency($impact['discount'], $cart->cart_currency_code),
                        'base_discount_amount' => $impact['discount']
                    ]);
                } else {
                    $item->update([
                        'discount_amount' => core()->currency($impact['discount'], $cart->cart_currency_code),
                        'base_discount_amount' => $impact['discount']
                    ]);
                }

                // save coupon if rule has it
                if ($rule->use_coupon) {
                    $coupon = $rule->coupons->code;

                    $item->update([
                        'coupon_code' => $coupon
                    ]);

                    $cart->update([
                        'coupon_code' => $coupon
                    ]);
                }

                break;
            }
        }

        Cart::collectTotals();

        return true;
    }

    /**
     * To find the least worth item in current cart instance
     *
     * @return array
     */
    public function leastWorthItem()
    {
        $cart = Cart::getCart();

        $leastValue = 999999999999;
        $leastWorthItem = [];

        foreach ($cart->items as $item) {
            if ($item->price < $leastValue) {
                $leastValue = $item->price;
                $leastWorthItem = [
                    'id' => $item->id,
                    'price' => $item->price,
                    'base_price' => $item->base_price,
                    'quantity' => $item->quantity
                ];
            }
        }

        return $leastWorthItem;
    }

    /**
     * To find the max worth item in current cart instance
     *
     * @return array
     */
    public function maxWorthItem()
    {
        $cart = Cart::getCart();

        $maxValue = 0;
        $maxWorthItem = [];

        foreach ($cart->items as $item) {
            if ($item->base_total > $maxValue) {
                $maxValue = $item->total;

                $maxWorthItem = [
                    'id' => $item->id,
                    'price' => $item->price,
                    'base_price' => $item->base_price,
                    'quantity' => $item->quantity
                ];
            }
        }

        return $maxWorthItem;
    }

    /**
     * Validate the currently applied cart rule
     *
     * @return boolean
     */
    public function validateRule($rule)
    {
        $applicability = $this->checkApplicability($rule);

        if ($applicability) {
            if ($rule->status) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Retreives all the payment methods from application config
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $paymentMethods = config('paymentmethods');

        return $paymentMethods;
    }

    /**
     * Retreives all the shippin methods from the application config
     *
     * @return array
     */
    public function getShippingMethods()
    {
        $shippingMethods = config('carriers');

        return $shippingMethods;
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