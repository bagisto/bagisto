<?php

namespace Webkul\CartRule\Helpers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\CartRule\Repositories\CartRuleCouponUsageRepository;
use Webkul\CartRule\Repositories\CartRuleCustomerRepository;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\Checkout\Contracts\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Rule\Helpers\Validator;

class CartRule
{
    /**
     * The cart instance currently being processed.
     *
     * @var Cart|null
     */
    protected $cart = null;

    /**
     * Cached collection of cart rules for the current request.
     *
     * @var Collection|null
     */
    protected $cartRules = null;

    /**
     * Per-collect memoization of `canProcessRule` results, keyed by rule id.
     *
     * @var array<int, bool>
     */
    protected $canProcessRuleCache = [];

    /**
     * Per-rule item totals used for `cart_fixed` action calculations.
     *
     * @var array<int, array{base_total_price: float, total_items: int}>
     */
    protected $itemTotals = [];

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CartRuleRepository $cartRuleRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository,
        protected CartRuleCustomerRepository $cartRuleCustomerRepository,
        protected CartRuleCouponUsageRepository $cartRuleCouponUsageRepository,
        protected Validator $validator
    ) {}

    /**
     * Collect cart rule discounts for the given cart.
     *
     * @param  Cart  $cart
     * @return void
     */
    public function collect($cart)
    {
        $this->cart = $cart;

        $this->canProcessRuleCache = [];

        /**
         * If cart rules are not available then don't process further.
         */
        if (
            ! $this->haveCartRules()
            && ! (float) $cart->base_discount_amount
        ) {
            return;
        }

        $appliedCartRuleIds = [];

        $this->calculateCartItemTotals();

        foreach ($cart->items as $item) {
            $itemCartRuleIds = $this->process($item);

            $appliedCartRuleIds = array_merge($appliedCartRuleIds, $itemCartRuleIds);

            if (
                $item->children->isNotEmpty()
                && $item->getTypeInstance()->isChildrenCalculated()
            ) {
                $this->divideDiscount($item);
            }
        }

        $this->cart->update([
            'applied_cart_rule_ids' => implode(',', array_unique($appliedCartRuleIds, SORT_REGULAR)),
        ]);

        $this->processShippingDiscount();

        $this->processFreeShippingDiscount();

        if (! $this->checkCouponCode()) {
            cart()->removeCouponCode();
        }
    }

    /**
     * Calculate cart item totals for each `cart_fixed` rule.
     *
     * @return void
     */
    public function calculateCartItemTotals()
    {
        foreach ($this->getCartRules() as $rule) {
            if ($rule->action_type != 'cart_fixed') {
                continue;
            }

            if (! $this->canProcessRule($rule)) {
                continue;
            }

            $totalBasePrice = $validCount = 0;

            foreach ($this->cart->items as $item) {
                if (! $this->validator->validate($rule, $item)) {
                    continue;
                }

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

    /**
     * Compute and persist discount values for a single cart item.
     *
     * @return array<int, int> Applied cart rule ids, keyed by rule id.
     */
    public function process(CartItem $item): array
    {
        $item->discount_percent = 0;
        $item->discount_amount = 0;
        $item->base_discount_amount = 0;

        $appliedRuleIds = [];

        foreach ($this->getCartRules() as $rule) {
            if (! $this->canProcessRule($rule)) {
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

                    $discountAmount = ($quantity * $item->price - $item->discount_amount) * ($rulePercent / 100);

                    $baseDiscountAmount = ($quantity * $item->base_price - $item->base_discount_amount) * ($rulePercent / 100);

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
                $item->price * $quantity
            );
            $item->base_discount_amount = min(
                $item->base_discount_amount + $baseDiscountAmount,
                $item->base_price * $quantity
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
     * Apply shipping discount rules to the selected shipping rate.
     *
     * @return self|void
     */
    public function processShippingDiscount()
    {
        if (! $selectedShipping = $this->cart->selected_shipping_rate) {
            return;
        }

        $selectedShipping->discount_amount = 0;
        $selectedShipping->base_discount_amount = 0;

        $appliedRuleIds = [];

        foreach ($this->getCartRules() as $rule) {
            if (! $this->canProcessRule($rule)) {
                continue;
            }

            if (! $this->validator->validate($rule, $this->cart)) {
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

            $appliedRuleIds[$rule->id] = $rule->id;

            if ($rule->end_other_rules) {
                break;
            }
        }

        $selectedShipping->save();

        $cartAppliedCartRuleIds = array_merge(explode(',', $this->cart->applied_cart_rule_ids), $appliedRuleIds);

        $cartAppliedCartRuleIds = array_filter($cartAppliedCartRuleIds);

        $cartAppliedCartRuleIds = array_unique($cartAppliedCartRuleIds);

        $this->cart->update([
            'applied_cart_rule_ids' => implode(',', $cartAppliedCartRuleIds),
        ]);

        return $this;
    }

    /**
     * Apply free-shipping cart rules to the selected shipping rate.
     *
     * @return void
     */
    public function processFreeShippingDiscount()
    {
        if (! $selectedShipping = $this->cart->selected_shipping_rate) {
            return;
        }

        $selectedShipping->discount_amount = 0;

        $selectedShipping->base_discount_amount = 0;

        $appliedRuleIds = [];

        $freeShippingApplied = false;

        $items = $this->cart->items->all();

        foreach ($this->getCartRules() as $rule) {
            if (! $rule->free_shipping) {
                continue;
            }

            if (! $this->canProcessRule($rule)) {
                continue;
            }

            $matched = false;

            foreach ($items as $item) {
                if ($this->validator->validate($rule, $item)) {
                    $matched = true;

                    break;
                }
            }

            if (! $matched) {
                continue;
            }

            if (! $freeShippingApplied) {
                $selectedShipping->price = 0;
                $selectedShipping->price_incl_tax = 0;
                $selectedShipping->base_price = 0;
                $selectedShipping->base_price_incl_tax = 0;

                $selectedShipping->save();

                $freeShippingApplied = true;
            }

            $appliedRuleIds[$rule->id] = $rule->id;

            if ($rule->end_other_rules) {
                break;
            }
        }

        $cartAppliedCartRuleIds = array_merge(explode(',', $this->cart->applied_cart_rule_ids), $appliedRuleIds);

        $cartAppliedCartRuleIds = array_filter($cartAppliedCartRuleIds);

        $cartAppliedCartRuleIds = array_unique($cartAppliedCartRuleIds);

        $this->cart->update([
            'applied_cart_rule_ids' => implode(',', $cartAppliedCartRuleIds),
        ]);
    }

    /**
     * Determine whether the cart's coupon code maps to an applied cart rule.
     */
    public function checkCouponCode(): bool
    {
        if (! $this->cart->coupon_code) {
            return true;
        }

        $coupons = $this->cartRuleCouponRepository->where(['code' => $this->cart->coupon_code])->get();

        foreach ($coupons as $coupon) {
            if (in_array($coupon->cart_rule_id, explode(',', $this->cart->applied_cart_rule_ids))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether any cart rules exist for the current channel and customer group.
     */
    public function haveCartRules(): bool
    {
        return $this->getCartRules()->isNotEmpty();
    }

    /**
     * Return the active cart rules for the current channel and customer group.
     *
     * @return Collection
     */
    public function getCartRules()
    {
        if ($this->cartRules) {
            return $this->cartRules;
        }

        $this->cartRules = $this->getCartRuleQuery()
            ->with([
                'cart_rule_customer_groups',
                'cart_rule_channels',
                'cart_rule_coupon',
            ])
            ->get();

        return $this->cartRules;
    }

    /**
     * Build the base query for fetching cart rules applicable to the current channel and customer group.
     *
     * @return Builder
     */
    public function getCartRuleQuery()
    {
        $customerGroup = $this->customerRepository->getCurrentGroup();

        return $this->cartRuleRepository
            ->leftJoin('cart_rule_customer_groups', 'cart_rules.id', '=', 'cart_rule_customer_groups.cart_rule_id')
            ->leftJoin('cart_rule_channels', 'cart_rules.id', '=', 'cart_rule_channels.cart_rule_id')
            ->where('cart_rule_customer_groups.customer_group_id', $customerGroup->id)
            ->where('cart_rule_channels.channel_id', core()->getCurrentChannel()->id)
            ->where(function ($query) {
                $query->where('cart_rules.starts_from', '<=', Carbon::now()->format('Y-m-d H:m:s'))
                    ->orWhereNull('cart_rules.starts_from');
            })
            ->where(function ($query) {
                $query->where('cart_rules.ends_till', '>=', Carbon::now()->format('Y-m-d H:m:s'))
                    ->orWhereNull('cart_rules.ends_till');
            })
            ->where('status', 1)
            ->orderBy('sort_order', 'asc');
    }

    /**
     * Check whether the given cart rule can be applied to the current cart.
     *
     * Results are memoized per rule for the lifetime of a `collect()` call.
     *
     * @param  \Webkul\CartRule\Contracts\CartRule  $rule
     */
    public function canProcessRule($rule): bool
    {
        if (array_key_exists($rule->id, $this->canProcessRuleCache)) {
            return $this->canProcessRuleCache[$rule->id];
        }

        return $this->canProcessRuleCache[$rule->id] = $this->resolveCanProcessRule($rule);
    }

    /**
     * Distribute the parent item's discount across its children proportionally.
     *
     * @param  \Webkul\Checkout\Contracts\CartItem  $item
     * @return void
     */
    protected function divideDiscount($item)
    {
        if (! $item->discount_amount && ! $item->base_discount_amount) {
            return;
        }

        foreach ($item->children as $child) {
            $ratio = $item->base_total != 0 ? $child->base_total / $item->base_total : 0;

            $dirty = false;

            foreach (['discount_amount', 'base_discount_amount'] as $column) {
                if (! $item->{$column}) {
                    continue;
                }

                $child->{$column} = round(($item->{$column} * $ratio), 4);

                $dirty = true;
            }

            if ($dirty) {
                $child->save();
            }
        }
    }

    /**
     * Resolve whether a cart rule can be applied (uncached).
     *
     * @param  \Webkul\CartRule\Contracts\CartRule  $rule
     */
    protected function resolveCanProcessRule($rule): bool
    {
        if ($rule->coupon_type) {
            if (! strlen($this->cart->coupon_code)) {
                return false;
            }

            /** @var \Webkul\CartRule\Models\CartRule $rule */
            // Use the eloquent relation rather than the coupon repository
            // here so the `cart_rule_id` scope is applied automatically and
            // we avoid the extra repository indirection.
            $coupon = $rule->cart_rule_coupon()->where('code', $this->cart->coupon_code)->first();

            if (
                $coupon
                && $coupon->code === $this->cart->coupon_code
            ) {
                if (
                    $coupon->usage_limit
                    && $coupon->times_used >= $coupon->usage_limit
                ) {
                    return false;
                }

                if (
                    $this->cart->customer_id
                    && $coupon->usage_per_customer
                ) {
                    $couponUsage = $this->cartRuleCouponUsageRepository->findOneWhere([
                        'cart_rule_coupon_id' => $coupon->id,
                        'customer_id' => $this->cart->customer_id,
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
                'customer_id' => $this->cart->customer_id,
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
}
