<?php

namespace Webkul\CartRule\Helpers;

use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

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
     * Create a new helper instance.
     *
     * @param  Webkul\CartRule\Repositories\CartRuleRepository       $cartRuleRepository
     * @param  Webkul\CartRule\Repositories\CartRuleCouponRepository $cartRuleCouponRepository
     * @return void
     */
    public function __construct(
        CartRuleRepository $cartRuleRepository,
        CartRuleCouponRepository $cartRuleCouponRepository
    )
    {
        $this->cartRuleRepository = $cartRuleRepository;

        $this->cartRuleCouponRepository = $cartRuleCouponRepository;
    }
}