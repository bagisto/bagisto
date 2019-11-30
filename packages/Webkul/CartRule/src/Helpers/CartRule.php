<?php

namespace Webkul\CartRule\Helpers;

use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\CartRule\Repositories\CartRuleCouponUsageRepository;
use Webkul\CartRule\Repositories\CartRuleCustomerRepository;
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
     * @param  Webkul\CartRule\Helpers\Validator                          $validator
     * @return void
     */
    public function __construct(
        CartRuleRepository $cartRuleRepository,
        CartRuleCouponRepository $cartRuleCouponRepository,
        CartRuleCouponUsageRepository $cartRuleCouponUsageRepository,
        CartRuleCustomerRepository $cartRuleCustomerRepository,
        Validator $validator
    )
    {
        $this->cartRuleRepository = $cartRuleRepository;

        $this->cartRuleCouponRepository = $cartRuleCouponRepository;

        $this->cartRuleCouponUsageRepository = $cartRuleCouponUsageRepository;

        $this->cartRuleCustomerRepository = $cartRuleCustomerRepository;

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
        }
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

        // Filter rules for current channel and current customer group
        return $cartRules = $this->cartRuleRepository->scopeQuery(function($query) {
            return $query->orderBy('sort_order', 'asc');
        })->findWhere([
            'status' => 1
        ]);
    }

    /**
     * Check if cart rule can be applied
     *
     * @param CartRule $rule
     * @param CartItem $item
     * @return boolean
     */
    public function canProcessRule($rule, $item)
    {
        $cart = Cart::getCart();

        if ($rule->coupon_type) {
            $isCouponValid = true;

            if (strlen($cart->coupon_code)) {
                $coupon = $this->cartRuleCouponRepository->findOneByField('code', $cart->coupon_code);

                if ($coupon) {
                    if ($coupon->usage_limit && $coupon->times_used >= $coupon->usage_limit)
                        $isCouponValid = false;
                    
                    if ($cart->customer_id && $coupon->usage_per_customer) {
                        $couponUsage = $this->cartRuleCouponUsageRepository->findOneWhere([
                                'coupon_id' => $cart->id,
                                'customer_id' => $cart->customer_id,
                            ]);

                        if ($couponUsage && $couponUsage->usage >= $coupon->usage_per_customer)
                            $isCouponValid = false;
                    }
                } else {
                    $isCouponValid = false;
                }
            } else {
                $isCouponValid = false;
            }

            if (! $isCouponValid) {
                Cart::removeCouponCode();

                return false;
            }
        }

        if ($rule->usage_per_customer) {
            $ruleCustomer = $this->cartRuleCustomerRepository->findOneWhere([
                    'rule_id' => $rule->id,
                    'customer_id' => $cart->customer_id,
                ]);

            if ($ruleCustomer && $ruleCustomer->usage_throttle >= $rule->usage_per_customer)
                return false;
        }

        if (! $this->validator->validate($rule, $item))
            return false;

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

        $appliedRuleIds = [];

        foreach ($this->getCartRules() as $rule) {
            if (! $this->canProcessRule($rule, $item))
                continue;

            $quantity = $rule->discount_quantity ? min($item->quantity, $rule->discount_quantity) : $item->quantity;

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

        return $this;
    }
}