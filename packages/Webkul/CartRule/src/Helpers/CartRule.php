<?php

namespace Webkul\CartRule\Helpers;

use Carbon\Carbon;
use Webkul\Checkout\Facades\Cart;
use Webkul\Rule\Helpers\Validator;
use Webkul\Checkout\Models\CartItem;
use Illuminate\Database\Eloquent\Builder;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\CartRule\Repositories\CartRuleCustomerRepository;
use Webkul\CartRule\Repositories\CartRuleCouponUsageRepository;

class CartRule
{
    /**
     * @var array
     */
    protected $itemTotals = [];

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\CartRule\Repositories\CartRuleRepository  $cartRuleRepository
     * @param  \Webkul\CartRule\Repositories\CartRuleCouponRepository  $cartRuleCouponRepository
     * @param  \Webkul\CartRule\Repositories\CartRuleCouponUsageRepository  $cartRuleCouponUsageRepository
     * @param  \Webkul\CartRule\Repositories\CartRuleCustomerRepository  $cartRuleCustomerRepository
     * @param  \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     * @param  \Webkul\Rule\Helpers\Validator  $validator
     *
     * @return void
     */
    public function __construct(
        protected CartRuleRepository $cartRuleRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository,
        protected CartRuleCouponUsageRepository $cartRuleCouponUsageRepository,
        protected CartRuleCustomerRepository $cartRuleCustomerRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected Validator $validator
    )
    {
    }

    /**
     * Collect discount on cart
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function collect($cart)
    {
        $appliedCartRuleIds = [];

        $this->calculateCartItemTotals($cart);

        foreach ($cart->items as $item) {
            $itemCartRuleIds = $this->process($item);

            $appliedCartRuleIds = array_merge($appliedCartRuleIds, $itemCartRuleIds);

            if (
                $item->children()->count()
                && $item->product->getTypeInstance()->isChildrenCalculated()
            ) {
                $this->divideDiscount($item);
            }
        }

        $cart->applied_cart_rule_ids = implode(',', array_unique($appliedCartRuleIds, SORT_REGULAR));
        $cart->save();
        $cart->refresh();

        $this->processShippingDiscount($cart);

        $this->processFreeShippingDiscount($cart);

        if (! $this->checkCouponCode($cart)) {
            cart()->removeCouponCode();
        }
    }

    /**
     * Returns cart rules
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return \Illuminate\Support\Collection
     */
    public function getCartRules($cart)
    {
        $staticCartRules = new class() {
            public static $cartRules;
            public static $cartID;
        };

        if (
            $staticCartRules::$cartID === $cart->id
            && $staticCartRules::$cartRules
        ) {
            return $staticCartRules::$cartRules;
        }

        $staticCartRules::$cartID = $cart->id;

        $customerGroupId = null;

        if (auth()->guard()->check()) {
            $customerGroupId = auth()->guard()->user()->customer_group_id;
        } else {
            $customerGuestGroup = $this->customerGroupRepository->getCustomerGuestGroup();

            if ($customerGuestGroup) {
                $customerGroupId = $customerGuestGroup->id;
            }
        }

        $cartRules = $this->getCartRuleQuery($customerGroupId, core()->getCurrentChannel()->id);

        $staticCartRules::$cartRules = $cartRules;
        
        return $cartRules;
    }

    /**
     * Check if cart rule can be applied
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @param  \Webkul\CartRule\Contracts\CartRule  $rule
     * @return bool
     */
    public function canProcessRule($cart, $rule): bool
    {
        if ($rule->coupon_type) {
            if (! strlen($cart->coupon_code)) {
                return false;
            }

            /** @var \Webkul\CartRule\Models\CartRule $rule */
            // Laravel relation is used instead of repository for performance
            // reasons (cart_rule_coupon-relation is pre-loaded by self::getCartRuleQuery())
            $coupon = $rule->cart_rule_coupon()->where('code', $cart->coupon_code)->first();

            if (
                $coupon
                && $coupon->code === $cart->coupon_code
            ) {
                if (
                    $coupon->usage_limit
                    && $coupon->times_used >= $coupon->usage_limit
                ) {
                    return false;
                }

                if (
                    $cart->customer_id
                    && $coupon->usage_per_customer
                ) {
                    $couponUsage = $this->cartRuleCouponUsageRepository->findOneWhere([
                        'cart_rule_coupon_id' => $coupon->id,
                        'customer_id'         => $cart->customer_id,
                    ]);

                    if (
                        $couponUsage
                        && $couponUsage->times_used >= $coupon->usage_per_customer
                    ) {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }

        if ($rule->usage_per_customer) {
            $ruleCustomer = $this->cartRuleCustomerRepository->findOneWhere([
                'cart_rule_id' => $rule->id,
                'customer_id'  => $cart->customer_id,
            ]);

            if (
                $ruleCustomer
                && $ruleCustomer->times_used >= $rule->usage_per_customer
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Cart item discount calculation process
     *
     * @param  \Webkul\Checkout\Models\CartItem  $item
     * @return array
     */
    public function process(CartItem $item): array
    {
        $item->discount_percent = 0;
        $item->discount_amount = 0;
        $item->base_discount_amount = 0;

        $appliedRuleIds = [];

        foreach ($rules = $this->getCartRules($item->cart) as $rule) {
            if (! $this->canProcessRule($item->cart, $rule)) {
                continue;
            }

            if (! $this->validator->validate($rule, $item)) {
                continue;
            }

            if ($rule->coupon_code) {
                $item->coupon_code = $rule->coupon_code;
            }

            $quantity = $rule->discount_quantity ? min($item->quantity, $rule->discount_quantity) : $item->quantity;

            $discountAmount = $baseDiscountAmount = 0;

            switch ($rule->action_type) {
                case 'by_percent':
                    $rulePercent = min(100, $rule->discount_amount);

                    $discountAmount = ($quantity * $item->price + $item->tax_amount - $item->discount_amount) * ($rulePercent / 100);

                    $baseDiscountAmount = ($quantity * $item->base_price + $item->base_tax_amount - $item->base_discount_amount) * ($rulePercent / 100);

                    if (
                        ! $rule->discount_quantity
                        || $rule->discount_quantity > $quantity
                    ) {
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

                    break;

                case 'buy_x_get_y':
                    if (
                        ! $rule->discount_step
                        || $rule->discount_amount > $rule->discount_step
                    ) {
                        break;
                    }

                    $buyAndDiscountQty = $rule->discount_step + $rule->discount_amount;

                    $qtyPeriod = floor($quantity / $buyAndDiscountQty);

                    $freeQty = $quantity - $qtyPeriod * $buyAndDiscountQty;

                    $discountQty = $qtyPeriod * $rule->discount_amount;

                    if ($freeQty > $rule->discount_step) {
                        $discountQty += $freeQty - $rule->discount_step;
                    }

                    $discountAmount = $discountQty * $item->price;

                    $baseDiscountAmount = $discountQty * $item->base_price;

                    break;
            }

            $item->discount_amount = min(
                $item->discount_amount + $discountAmount,
                $item->price * $quantity + $item->tax_amount
            );
            $item->base_discount_amount = min(
                $item->base_discount_amount + $baseDiscountAmount,
                $item->base_price * $quantity + $item->base_tax_amount
            );

            $appliedRuleIds[$rule->id] = $rule->id;

            if ($rule->end_other_rules) {
                break;
            }
        }

        $item->applied_cart_rule_ids = implode(',', $appliedRuleIds);

        $item->save();

        return $appliedRuleIds;
    }

    /**
     * Cart shipping discount calculation process
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return self|void
     */
    public function processShippingDiscount($cart)
    {
        if (! $selectedShipping = $cart->selected_shipping_rate) {
            return;
        }

        $selectedShipping->discount_amount = 0;
        $selectedShipping->base_discount_amount = 0;

        $appliedRuleIds = [];

        foreach ($this->getCartRules($cart) as $rule) {
            if (! $this->canProcessRule($cart, $rule)) {
                continue;
            }

            if (! $this->validator->validate($rule, $cart)) {
                continue;
            }

            if (
                ! $rule
                || ! $rule->apply_to_shipping
            ) {
                continue;
            }

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

            if ($rule->end_other_rules) {
                break;
            }
        }

        $selectedShipping->save();

        $cartAppliedCartRuleIds = array_merge(explode(',', $cart->applied_cart_rule_ids), $appliedRuleIds);

        $cartAppliedCartRuleIds = array_filter($cartAppliedCartRuleIds);

        $cartAppliedCartRuleIds = array_unique($cartAppliedCartRuleIds);

        $cart->applied_cart_rule_ids = implode(',', $cartAppliedCartRuleIds);

        $cart->save();

        return $this;
    }

    /**
     * Cart free shipping discount calculation process
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function processFreeShippingDiscount($cart)
    {
        if (! $selectedShipping = $cart->selected_shipping_rate) {
            return;
        }

        $selectedShipping->discount_amount = 0;

        $selectedShipping->base_discount_amount = 0;

        $appliedRuleIds = [];

        foreach ($cart->items->all() as $item) {
            foreach ($this->getCartRules($cart) as $rule) {
                if (! $this->canProcessRule($cart, $rule)) {
                    continue;
                }

                /* given CartItem instance to the validator */
                if (! $this->validator->validate($rule, $item)) {
                    continue;
                }

                if (
                    ! $rule
                    || ! $rule->free_shipping
                ) {
                    continue;
                }

                $selectedShipping->price = 0;

                $selectedShipping->base_price = 0;

                $selectedShipping->save();

                $appliedRuleIds[$rule->id] = $rule->id;

                if ($rule->end_other_rules) {
                    break;
                }
            }
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
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return array|void
     */
    public function calculateCartItemTotals($cart)
    {
        foreach ($this->getCartRules($cart) as $rule) {
            if ($rule->action_type != 'cart_fixed') {
                continue;
            }

            $totalPrice = $totalBasePrice = $validCount = 0;

            foreach ($cart->items as $item) {
                if (! $this->canProcessRule($cart, $rule)) {
                    continue;
                }

                if (! $this->validator->validate($rule, $item)) {
                    continue;
                }

                $quantity = $rule->discount_quantity ? min($item->quantity, $rule->discount_quantity) : $item->quantity;

                $totalBasePrice += $item->base_price * $quantity;

                $validCount++;
            }

            $this->itemTotals[$rule->id] = [
                'base_total_price' => $totalBasePrice,
                'total_items'      => $validCount,
            ];
        }
    }

    /**
     * Check if coupon code is applied or not
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return bool
     */
    public function checkCouponCode($cart): bool
    {
        if (! $cart->coupon_code) {
            return true;
        }

        $coupons = $this->cartRuleCouponRepository->where(['code' => $cart->coupon_code])->get();

        foreach ($coupons as $coupon) {
            if (in_array($coupon->cart_rule_id, explode(',', $cart->applied_cart_rule_ids))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Divide discount amount to children
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return void
     */
    protected function divideDiscount($item)
    {
        foreach ($item->children as $child) {
            $ratio = $item->base_total != 0 ? $child->base_total / $item->base_total : 0;

            foreach (['discount_amount', 'base_discount_amount'] as $column) {
                if (! $item->{$column}) {
                    continue;
                }

                $child->{$column} = round(($item->{$column} * $ratio), 4);

                $child->save();
            }
        }
    }

    /**
     * @param  integer  $customerGroupId
     * @param  integer  $channelId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCartRuleQuery($customerGroupId, $channelId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->cartRuleRepository->scopeQuery(function ($query) use ($customerGroupId, $channelId) {
            /** @var Builder $query */
            return $query->leftJoin('cart_rule_customer_groups', 'cart_rules.id', '=',
                'cart_rule_customer_groups.cart_rule_id')
                ->leftJoin('cart_rule_channels', 'cart_rules.id', '=', 'cart_rule_channels.cart_rule_id')
                ->where('cart_rule_customer_groups.customer_group_id', $customerGroupId)
                ->where('cart_rule_channels.channel_id', $channelId)
                ->where(function ($query1) {
                    /** @var Builder $query1 */
                    $query1->where('cart_rules.starts_from', '<=', Carbon::now()->format('Y-m-d H:m:s'))
                        ->orWhereNull('cart_rules.starts_from');
                })
                ->where(function ($query2) {
                    /** @var Builder $query2 */
                    $query2->where('cart_rules.ends_till', '>=', Carbon::now()->format('Y-m-d H:m:s'))
                        ->orWhereNull('cart_rules.ends_till');
                })
                ->with([
                    'cart_rule_customer_groups',
                    'cart_rule_channels',
                    'cart_rule_coupon'
                ])
                ->orderBy('sort_order', 'asc');
        })->findWhere(['status' => 1]);
    }
}
