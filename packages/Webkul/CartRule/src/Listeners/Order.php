<?php

namespace Webkul\CartRule\Listeners;

use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\CartRule\Repositories\CartRuleCustomerRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\CartRule\Repositories\CartRuleCouponUsageRepository;

/**
 * Order event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order
{
    /**
     * CartRuleRepository object
     *
     * @var CartRuleRepository
     */
    protected $cartRuleRepository;

    /**
     * CartRuleCustomerRepository object
     *
     * @var CartRuleCustomerRepository
     */
    protected $cartRuleCustomerRepository;

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
     * Create a new listener instance.
     *
     * @param  Webkul\CartRule\Repositories\CartRuleRepository            $cartRuleRepository
     * @param  Webkul\CartRule\Repositories\CartRuleCustomerRepository    $cartRuleCustomerRepository
     * @param  Webkul\CartRule\Repositories\CartRuleCouponRepository      $cartRuleCouponRepository
     * @param  Webkul\CartRule\Repositories\CartRuleCouponUsageRepository $cartRuleCouponUsageRepository
     * @return void
     */
    public function __construct(
        CartRuleRepository $cartRuleRepository,
        CartRuleCustomerRepository $cartRuleCustomerRepository,
        CartRuleCouponRepository $cartRuleCouponRepository,
        CartRuleCouponUsageRepository $cartRuleCouponUsageRepository
    )
    {
        $this->cartRuleRepository = $cartRuleRepository;

        $this->cartRuleCustomerRepository = $cartRuleCustomerRepository;

        $this->cartRuleCouponRepository = $cartRuleCouponRepository;

        $this->cartRuleCouponUsageRepository = $cartRuleCouponUsageRepository;
    }

    /**
     * Save cart rule and cart rule coupon properties after place order
     * 
     * @param Order $order
     * @return void
     */
    public function manageCartRule($order)
    {
        if (! $order->discount_amount)
            return;

        $cartRuleIds = explode(',', $order->applied_cart_rule_ids);

        $cartRuleIds = array_unique($cartRuleIds);

        foreach ($cartRuleIds as $ruleId) {
            $rule = $this->cartRuleRepository->find($ruleId);

            if (! $rule)
                continue;

            $rule->update(['times_used' => $rule->times_used + 1]);

            if (! $order->customer_id)
                continue;

            $ruleCustomer = $this->cartRuleCustomerRepository->findOneWhere([
                    'customer_id' => $order->customer_id,
                    'cart_rule_id' => $ruleId
                ]);

            if ($ruleCustomer) {
                $this->cartRuleCustomerRepository->update(['times_used' => $ruleCustomer->times_used + 1], $ruleCustomer->id);
            } else {
                $this->cartRuleCustomerRepository->create([
                        'customer_id' => $order->customer_id,
                        'cart_rule_id' => $ruleId,
                        'times_used' => 1
                    ]);
            }
        }

        if (! $order->coupon_code)
            return;

        $coupon = $this->cartRuleCouponRepository->findOneByField('code', $order->coupon_code);
        
        if ($coupon) {
            $this->cartRuleCouponRepository->update(['times_used' => $coupon->times_used + 1], $coupon->id);

            if ($order->customer_id) {
                $couponUsage = $this->cartRuleCouponUsageRepository->findOneWhere([
                        'customer_id' => $order->customer_id,
                        'cart_rule_coupon_id' => $coupon->id
                    ]);

                if ($couponUsage) {
                    $this->cartRuleCouponUsageRepository->update(['times_used' => $couponUsage->times_used + 1], $couponUsage->id);
                } else {
                    $this->cartRuleCouponUsageRepository->create([
                            'customer_id' => $order->customer_id,
                            'cart_rule_coupon_id' => $coupon->id,
                            'times_used' => 1
                        ]);
                }
            }
        }
    }
}