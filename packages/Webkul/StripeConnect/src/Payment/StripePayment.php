<?php

namespace Webkul\StripeConnect\Payment;

/**
 * StripePayment method class
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class StripePayment extends Stripe
{
    protected $code = 'stripe';

    /**
     * Get the redirect url for redirecting to
     */
    public function getRedirectUrl()
    {
        return route('stripe.make.payment');
    }

    /**
     * Stripe web URL generic getter
     *
     * @param array $params
     * @return string
     */
    public function getStripeUrl($params = [])
    {
        $this->getRedirectUrl();
    }
}