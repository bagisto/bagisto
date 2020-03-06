<?php

namespace Webkul\CartRule\Repositories;

use Webkul\Core\Eloquent\Repository;

class CartRuleCouponRepository extends Repository
{
    /**
     * @var array
     */
    protected $charsets = [
        'alphanumeric' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
        'alphabetical' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'numeric'      => '0123456789',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CartRule\Contracts\CartRuleCoupon';
    }

    /**
     * Creates coupons for cart rule
     *
     * @param  array  $data
     * @param  int  $cartRuleId
     * @return void
     */
    public function generateCoupons($data, $cartRuleId)
    {
        $cartRule = app('Webkul\CartRule\Repositories\CartRuleRepository')->findOrFail($cartRuleId);

        for ($i = 0; $i < $data['coupon_qty']; $i++) {
            parent::create([
                'cart_rule_id'       => $cartRuleId,
                'code'               => $data['code_prefix'] . $this->getRandomString($data['code_format'], $data['code_length']) . $data['code_suffix'],
                'usage_limit'        => $cartRule->uses_per_coupon ?? 0,
                'usage_per_customer' => $cartRule->usage_per_customer ?? 0,
                'is_primary'         => 0,
                'expired_at'         => $cartRule->ends_till ?: null,
            ]);
        }
    }

    /**
     * Creates coupons for cart rule
     *
     * @param  string  $format
     * @param  int  $length
     * @return string
     */
    public function getRandomString($format, $length)
    {
        $couponCode = '';

        for ($i = 0; $i < $length; $i++) {
            $couponCode .= $this->charsets[$format][rand(0, strlen($this->charsets[$format]) - 1)];
        }

        return $couponCode;
    }
}