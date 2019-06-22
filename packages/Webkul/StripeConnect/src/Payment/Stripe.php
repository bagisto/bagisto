<?php

namespace Webkul\StripeConnect\Payment;

// Payment package's main class
use Webkul\Payment\Payment\Payment;

use Illuminate\Support\Collection;

// Stripe Payments Classes
use Stripe\Card as StripeCard;
use Stripe\Token as StripeToken;
use Stripe\Charge as StripeCharge;
use Stripe\Refund as StripeRefund;
use Stripe\Invoice as StripeInvoice;
use Stripe\Customer as StripeCustomer;
use Stripe\InvoiceItem as StripeInvoiceItem;

// Exception classes that needs to be required
use Exception;
use InvalidArgumentException;
use Stripe\Error\InvalidRequest as StripeErrorInvalidRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Stripe class
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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
        return route('stripe.make.payment');
    }
}