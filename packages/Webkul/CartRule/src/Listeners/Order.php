<?php

namespace Webkul\CartRule\Listeners;

use Webkul\CartRule\Exceptions\CouponUsageLimitExceededException;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\CartRule\Repositories\CartRuleCouponUsageRepository;
use Webkul\CartRule\Repositories\CartRuleCustomerRepository;
use Webkul\CartRule\Repositories\CartRuleRepository;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRuleRepository $cartRuleRepository,
        protected CartRuleCustomerRepository $cartRuleCustomerRepository,
        protected CartRuleCouponRepository $cartRuleCouponRepository,
        protected CartRuleCouponUsageRepository $cartRuleCouponUsageRepository
    ) {}

    /**
     * Validate and record cart rule and coupon usage atomically after order save.
     *
     * This listener runs inside the order creation transaction. It acquires
     * row-level locks on coupon and usage records, re-validates usage limits,
     * and increments counters atomically. If limits are exceeded (due to a
     * concurrent request), it throws an exception to roll back the order.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     *
     * @throws CouponUsageLimitExceededException
     */
    public function manageCartRule($order)
    {
        if (! $order->discount_amount) {
            return;
        }

        $this->processCartRuleUsage($order);

        $this->processCouponUsage($order);
    }

    /**
     * Process and increment cart rule usage with per-customer validation.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     *
     * @throws CouponUsageLimitExceededException
     */
    protected function processCartRuleUsage($order): void
    {
        $cartRuleIds = array_unique(array_filter(explode(',', $order->applied_cart_rule_ids)));

        foreach ($cartRuleIds as $ruleId) {
            $rule = $this->cartRuleRepository
                ->getModel()
                ->newQuery()
                ->lockForUpdate()
                ->find($ruleId);

            if (! $rule) {
                continue;
            }

            $rule->increment('times_used');

            if (! $order->customer_id) {
                continue;
            }

            $ruleCustomer = $this->cartRuleCustomerRepository
                ->getModel()
                ->newQuery()
                ->lockForUpdate()
                ->where('customer_id', $order->customer_id)
                ->where('cart_rule_id', $ruleId)
                ->first();

            if ($ruleCustomer) {
                if (
                    $rule->usage_per_customer
                    && $ruleCustomer->times_used >= $rule->usage_per_customer
                ) {
                    throw new CouponUsageLimitExceededException(
                        trans('shop::app.checkout.coupon.usage-limit-exceeded')
                    );
                }

                $ruleCustomer->increment('times_used');
            } else {
                $this->cartRuleCustomerRepository->create([
                    'customer_id' => $order->customer_id,
                    'cart_rule_id' => $ruleId,
                    'times_used' => 1,
                ]);
            }
        }
    }

    /**
     * Process and increment coupon usage with global and per-customer validation.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     *
     * @throws CouponUsageLimitExceededException
     */
    protected function processCouponUsage($order): void
    {
        if (! $order->coupon_code) {
            return;
        }

        $coupon = $this->cartRuleCouponRepository
            ->getModel()
            ->newQuery()
            ->lockForUpdate()
            ->where('code', $order->coupon_code)
            ->first();

        if (! $coupon) {
            return;
        }

        /**
         * Validate global coupon usage limit. The lock ensures only one
         * transaction can read and increment this value at a time.
         */
        if (
            $coupon->usage_limit
            && $coupon->times_used >= $coupon->usage_limit
        ) {
            throw new CouponUsageLimitExceededException(
                trans('shop::app.checkout.coupon.usage-limit-exceeded')
            );
        }

        /**
         * Validate per-customer coupon usage limit.
         */
        $couponUsage = null;

        if ($order->customer_id && $coupon->usage_per_customer) {
            $couponUsage = $this->cartRuleCouponUsageRepository
                ->getModel()
                ->newQuery()
                ->lockForUpdate()
                ->where('customer_id', $order->customer_id)
                ->where('cart_rule_coupon_id', $coupon->id)
                ->first();

            if (
                $couponUsage
                && $couponUsage->times_used >= $coupon->usage_per_customer
            ) {
                throw new CouponUsageLimitExceededException(
                    trans('shop::app.checkout.coupon.usage-limit-exceeded')
                );
            }
        }

        /**
         * All limits validated under lock — safe to increment.
         */
        $coupon->increment('times_used');

        if (! $order->customer_id) {
            return;
        }

        $couponUsage = $couponUsage ?? $this->cartRuleCouponUsageRepository->findOneWhere([
            'customer_id' => $order->customer_id,
            'cart_rule_coupon_id' => $coupon->id,
        ]);

        if ($couponUsage) {
            $couponUsage->increment('times_used');
        } else {
            $this->cartRuleCouponUsageRepository->create([
                'customer_id' => $order->customer_id,
                'cart_rule_coupon_id' => $coupon->id,
                'times_used' => 1,
            ]);
        }
    }
}
