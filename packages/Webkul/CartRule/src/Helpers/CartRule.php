<?php

namespace Webkul\CartRule\Helpers;

use Carbon\Carbon;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\CartRule\Repositories\CartRuleCouponUsageRepository;
use Webkul\CartRule\Repositories\CartRuleCustomerRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Rule\Helpers\Validator;
use Webkul\Checkout\Facades\Cart;

class CartRule
{
    /**
     * CartRuleRepository object
     *
     * @var CartRuleRepository
     */
    protected $cartRuleRepository;

    /**
     * CartRuleCouponRepository object
     *
     * @var CartRuleCouponRepository
     */
    protected $cartRuleCouponRepository;

    /**
     * CartRuleCouponUsageRepository object
     *
     * @var CartRuleCouponUsageRepository
     */
    protected $cartRuleCouponUsageRepository;

    /**
     * CartRuleCustomerRepository object
     *
     * @var CartRuleCustomerRepository
     */
    protected $cartRuleCustomerRepository;

    /**
     * CustomerGroupRepository object
     *
     * @var CustomerGroupRepository
     */
    protected $customerGroupRepository;

    /**
     * Validator object
     *
     * @var Validator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $itemTotals = [];

    /**
     * Create a new helper instance.
     *
     * @param  Webkul\CartRule\Repositories\CartRuleRepository            $cartRuleRepository
     * @param  Webkul\CartRule\Repositories\CartRuleCouponRepository      $cartRuleCouponRepository
     * @param  Webkul\CartRule\Repositories\CartRuleCouponUsageRepository $cartRuleCouponUsageRepository
     * @param  Webkul\CartRule\Repositories\CartRuleCustomerRepository    $cartRuleCustomerRepository
     * @param  Webkul\Customer\Repositories\CustomerGroupRepository       $customerGroupRepository
     * @param  Webkul\Rule\Helpers\Validator                              $validator
     * @return void
     */
    public function __construct(
        CartRuleRepository $cartRuleRepository,
        CartRuleCouponRepository $cartRuleCouponRepository,
        CartRuleCouponUsageRepository $cartRuleCouponUsageRepository,
        CartRuleCustomerRepository $cartRuleCustomerRepository,
        CustomerGroupRepository $customerGroupRepository,
        Validator $validator
    )
    {
        $this->cartRuleRepository = $cartRuleRepository;

        $this->cartRuleCouponRepository = $cartRuleCouponRepository;

        $this->cartRuleCouponUsageRepository = $cartRuleCouponUsageRepository;

        $this->cartRuleCustomerRepository = $cartRuleCustomerRepository;

        $this->customerGroupRepository = $customerGroupRepository;

        $this->validator = $validator;
    }

    /**
     * Collect discount on cart
     *
     * @return void
     */
    public function collect()
    {
        $cart = Cart::getCart();

        $this->calculateCartItemTotals($cart->items()->get());

        foreach ($cart->items()->get() as $item) {
            $this->process($item);

            if ($item->children()->count() && $item->product->getTypeInstance()->isChildrenCalculated())
                $this->devideDiscount($item);
        }

        $this->processShippingDiscount($cart);

        $this->processFreeShippingDiscount($cart);

        $this->validateCouponCode();
    }

    /**
     * Returns cart rules
     *
     * @return Collection
     */
    public function getCartRules()
    {
        static $cartRules;

        if ($cartRules)
            return $cartRules;

        $customerGroupId = null;

        if (Cart::getCurrentCustomer()->check()) {
            $customerGroupId = Cart::getCurrentCustomer()->user()->customer_group_id;
        } else {
            if ($customerGuestGroup = $this->customerGroupRepository->findOneByField('code', 'guest'))
                $customerGroupId = $customerGuestGroup->id;
        }

        $cartRules = $this->cartRuleRepository->scopeQuery(function($query) use ($customerGroupId) {
            return $query->leftJoin('cart_rule_customer_groups', 'cart_rules.id', '=', 'cart_rule_customer_groups.cart_rule_id')
                    ->leftJoin('cart_rule_channels', 'cart_rules.id', '=', 'cart_rule_channels.cart_rule_id')
                    ->where('cart_rule_customer_groups.customer_group_id', $customerGroupId)
                    ->where('cart_rule_channels.channel_id', core()->getCurrentChannel()->id)
                    ->where(function ($query1) {
                        $query1->where('cart_rules.starts_from', '<=', Carbon::now()->format('Y-m-d'))->orWhereNull('cart_rules.starts_from');
                    })
                    ->where(function ($query2) {
                        $query2->where('cart_rules.ends_till', '>=', Carbon::now()->format('Y-m-d'))->orWhereNull('cart_rules.ends_till');
                    })
                    ->orderBy('sort_order', 'asc');
        })->findWhere(['status' => 1]);

        return $cartRules;
    }

    /**
     * Check if cart rule can be applied
     *
     * @param CartRule $rule
     * @return boolean
     */
    public function canProcessRule($rule)
    {
        $cart = Cart::getCart();

        if ($rule->coupon_type) {
            if (strlen($cart->coupon_code)) {
                $coupon = $this->cartRuleCouponRepository->findOneWhere([
                        'cart_rule_id' => $rule->id,
                        'code' => $cart->coupon_code,
                    ]);

                if ($coupon) {
                    if ($coupon->usage_limit && $coupon->times_used >= $coupon->usage_limit)
                        return false;
                    
                    if ($cart->customer_id && $coupon->usage_per_customer) {
                        $couponUsage = $this->cartRuleCouponUsageRepository->findOneWhere([
                                'cart_rule_coupon_id' => $coupon->id,
                                'customer_id' => $cart->customer_id
                            ]);

                        if ($couponUsage && $couponUsage->times_used >= $coupon->usage_per_customer)
                            return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        if ($rule->usage_per_customer) {
            $ruleCustomer = $this->cartRuleCustomerRepository->findOneWhere([
                    'cart_rule_id' => $rule->id,
                    'customer_id' => $cart->customer_id,
                ]);

            if ($ruleCustomer && $ruleCustomer->times_used >= $rule->usage_per_customer)
                return false;
        }

        return true;
    }

    /**
     * Cart item discount calculation process
     *
     * @param CartItem $item
     * @return void
     */
    public function process($item)
    {
        $item->discount_percent = 0;
        $item->discount_amount = 0;
        $item->base_discount_amount = 0;

        $cart = $item->cart;

        $cart->applied_cart_rule_ids = null;

        $appliedRuleIds = [];

        foreach ($this->getCartRules() as $rule) {
            if (! $this->canProcessRule($rule))
                continue;

            if (! $this->validator->validate($rule, $item))
                continue;

            $quantity = $rule->discount_quantity ? min($item->quantity, $rule->discount_quantity) : $item->quantity;

            $discountAmount = $baseDiscountAmount = 0;

            switch ($rule->action_type) {
                case 'by_percent':
                    $rulePercent = min(100, $rule->discount_amount);

                    $discountAmount = ($quantity * $item->price - $item->discount_amount) * ($rulePercent / 100);

                    $baseDiscountAmount = ($quantity * $item->base_price - $item->base_discount_amount) * ($rulePercent / 100);

                    if (! $rule->discount_quantity || $rule->discount_quantity > $quantity) {
                        $discountPercent = min(100, $item->discount_percent + $rulePercent);

                        $item->discount_percent = $discountPercent;
                    }

                    break;

                case 'by_fixed':
                    $discountAmount = $quantity * core()->convertPrice($rule->discount_amount);

                    $baseDiscountAmount = $quantity * $rule->discount_amount;

                    break;

                case 'cart_fixed':
                    if ($this->itemTotals[$rule->id]['total_items'] <= 1) {
                        $discountAmount = core()->convertPrice($rule->discount_amount);

                        $baseDiscountAmount = min($item->base_price * $quantity, $rule->discount_amount);
                    } else {
                        $discountRate = $item->base_price * $quantity / $this->itemTotals[$rule->id]['base_total_price'];

                        $maxDiscount = $rule->discount_amount * $discountRate;

                        $discountAmount = core()->convertPrice($maxDiscount);

                        $baseDiscountAmount = min($item->base_price * $quantity, $maxDiscount);
                    }

                    $discountAmount = min($item->price * $quantity, $discountAmount);

                    break;

                case 'buy_x_get_y':
                    if (! $rule->discount_step || $rule->discount_amount > $rule->discount_step)
                        break;

                    $buyAndDiscountQty = $rule->discount_step + $rule->discount_amount;

                    $qtyPeriod = floor($quantity / $buyAndDiscountQty);

                    $freeQty = $quantity - $qtyPeriod * $buyAndDiscountQty;

                    $discountQty = $qtyPeriod * $rule->discount_amount;

                    if ($freeQty > $rule->discount_step)
                        $discountQty += $freeQty - $rule->discount_step;

                    $discountAmount = $discountQty * $item->price;

                    $baseDiscountAmount = $discountQty * $item->base_price;

                    break;
            }

            $item->discount_amount = min($item->discount_amount + $discountAmount, $item->price * $quantity);
            $item->base_discount_amount = min($item->base_discount_amount + $baseDiscountAmount, $item->base_price * $quantity);

            $appliedRuleIds[$rule->id] = $rule->id;

            if ($rule->end_other_rules)
                break;
        }

        $item->applied_cart_rule_ids = join(',', $appliedRuleIds);

        $item->save();

        $cartAppliedCartRuleIds = array_merge(explode(',', $cart->applied_cart_rule_ids), $appliedRuleIds);

        $cartAppliedCartRuleIds = array_filter($cartAppliedCartRuleIds);

        $cartAppliedCartRuleIds = array_unique($cartAppliedCartRuleIds);

        $cart->applied_cart_rule_ids = join(',', $cartAppliedCartRuleIds);

        $cart->save();
    }

    /**
     * Cart shipping discount calculation process
     *
     * @param Cart $cart
     * @return void
     */
    public function processShippingDiscount($cart)
    {
        if (! $selectedShipping = $cart->selected_shipping_rate)
            return;

        $selectedShipping->discount_amount = 0;
        $selectedShipping->base_discount_amount = 0;

        $appliedRuleIds = [];

        foreach ($this->getCartRules() as $rule) {
            if (! $this->canProcessRule($rule))
                continue;

            if (! $this->validator->validate($rule, $cart))
                continue;

            if (! $rule || ! $rule->apply_to_shipping)
                continue;

            $discountAmount = $baseDiscountAmount = 0;

            switch ($rule->action_type) {
                case 'by_percent':
                    $rulePercent = min(100, $rule->discount_amount);

                    $discountAmount = ($selectedShipping->price - $selectedShipping->discount_amount) * $rulePercent / 100;

                    $baseDiscountAmount = ($selectedShipping->base_price - $selectedShipping->base_discount_amount) * $rulePercent / 100;

                    break;

                case 'by_fixed':
                    $discountAmount = core()->convertPrice($rule->discount_amount);

                    $baseDiscountAmount = $rule->discount_amount;

                    break;
            }

            $selectedShipping->discount_amount = min($selectedShipping->discount_amount + $discountAmount, $selectedShipping->price);

            $selectedShipping->base_discount_amount = min(
                    $selectedShipping->base_discount_amount + $baseDiscountAmount,
                    $selectedShipping->base_price
                );

            $selectedShipping->save();

            $appliedRuleIds[$rule->id] = $rule->id;

            if ($rule->end_other_rules)
                break;
        }

        $selectedShipping->save();

        $cartAppliedCartRuleIds = array_merge(explode(',', $cart->applied_cart_rule_ids), $appliedRuleIds);

        $cartAppliedCartRuleIds = array_filter($cartAppliedCartRuleIds);

        $cartAppliedCartRuleIds = array_unique($cartAppliedCartRuleIds);

        $cart->applied_cart_rule_ids = join(',', $cartAppliedCartRuleIds);

        $cart->save();

        return $this;
    }

    /**
     * Cart free shipping discount calculation process
     *
     * @param Cart $cart
     * @return void
     */
    public function processFreeShippingDiscount($cart)
    {
        if (! $selectedShipping = $cart->selected_shipping_rate)
            return;

        $selectedShipping->discount_amount = 0;

        $selectedShipping->base_discount_amount = 0;

        $appliedRuleIds = [];

        foreach ($this->getCartRules() as $rule) {
            if (! $this->canProcessRule($rule))
                continue;

            if (! $this->validator->validate($rule, $cart))
                continue;

            if (! $rule || ! $rule->free_shipping)
                continue;

            $selectedShipping->price = 0;

            $selectedShipping->base_price = 0;

            $selectedShipping->save();

            $appliedRuleIds[$rule->id] = $rule->id;

            if ($rule->end_other_rules)
                break;
        }

        $cartAppliedCartRuleIds = array_merge(explode(',', $cart->applied_cart_rule_ids), $appliedRuleIds);

        $cartAppliedCartRuleIds = array_filter($cartAppliedCartRuleIds);

        $cartAppliedCartRuleIds = array_unique($cartAppliedCartRuleIds);

        $cart->applied_cart_rule_ids = join(',', $cartAppliedCartRuleIds);

        $cart->save();
    }

    /**
     * Calculate cart item totals for each rule
     *
     * @param mixed $items
     * @return Validator
     */
    public function calculateCartItemTotals($items)
    {
        foreach ($this->getCartRules() as $rule) {
            if ($rule->action_type == 'cart_fixed') {
                $totalPrice = $totalBasePrice = $validCount = 0;

                foreach ($items as $item) {
                    if (! $this->canProcessRule($rule, $item))
                        continue;

                    $quantity = $rule->discount_quantity ? min($item->quantity, $rule->discount_quantity) : $item->quantity;

                    $totalBasePrice += $item->base_price * $quantity;

                    $validCount++;
                }

                $this->itemTotals[$rule->id] = [
                    'base_total_price' => $totalBasePrice,
                    'total_items' => $validCount,
                ];
            }
        }
    }

    /**
     * Check if coupon code is valid or not, if not remove from cart
     *
     * @return void
     */
    public function validateCouponCode()
    {
        $cart = Cart::getCart();

        if (! $cart->coupon_code)
            return;

        $coupon = $this->cartRuleCouponRepository->findOneByField('code', $cart->coupon_code);

        if (! $coupon || ! in_array($coupon->cart_rule_id, explode(',', $cart->applied_cart_rule_ids)))
            Cart::removeCouponCode();
    }

    /**
     * Devide discount amount to children
     *
     * @param CartItem $item
     * @return void
     */
    protected function devideDiscount($item)
    {
        foreach ($item->children as $child) {
            $ratio = $item->base_total != 0 ? $child->base_total / $item->base_total : 0;

            foreach (['discount_amount', 'base_discount_amount'] as $column) {
                if (! $item->{$column})
                    continue;

                $child->{$column} = round(($item->{$column} * $ratio), 4);

                $child->save();
            }
        }
    }
}