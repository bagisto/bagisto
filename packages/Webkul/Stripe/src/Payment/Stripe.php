<?php

namespace Webkul\Stripe\Payment;

use Webkul\Payment\Payment\Payment;

abstract class Stripe extends Payment
{
    /**
     * To hold the stripe live API key
     */
    protected $apiKey;

    /**
     * To hold the instance of the sku intance of the stripe API
     */
    protected $sku;

    /**
     * Bank statement descriptor, to hold the statement descriptor value
     */
    protected $statementDescriptor;

    /**
     * Checkout.js link for payment processing
     */
    protected $checkoutLink;

    /**
     * To redirect to the stripe payment page
     */
    public function getStripeUrl()
    {
        return route('stripe.standard.redirect');
    }
}
