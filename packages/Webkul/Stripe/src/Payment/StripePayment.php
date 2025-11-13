<?php

namespace Webkul\Stripe\Payment;

use Illuminate\Support\Facades\Storage;

/**
 * StripePayment method class
 */
class StripePayment extends Stripe
{
    protected $code = 'stripe';

    /**
     * Get the redirect url for redirecting to
     */
    public function getRedirectUrl()
    {
        return route('stripe.standard.redirect');
    }

    /**
     * Stripe web URL generic getter
     *
     * @param  array  $params
     * @return string
     */
    public function getStripeUrl($params = [])
    {
        $this->getRedirectUrl();
    }

    /**
     * Stripe log
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/stripe.png', 'shop');
    }
}
