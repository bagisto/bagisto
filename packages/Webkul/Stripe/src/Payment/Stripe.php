<?php

namespace Webkul\Stripe\Payment;

use Illuminate\Support\Facades\Storage;
use Stripe\Checkout\Session;
use Stripe\Stripe as BaseStripe;
use Webkul\Checkout\Facades\Cart;
use Webkul\Payment\Payment\Payment;

class Stripe extends Payment
{
    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'stripe';

    /**
     * Get redirect URL for Stripe payment.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('stripe.standard.redirect');
    }

    /**
     * Check if payment method is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return parent::isAvailable() && $this->hasValidCredentials();
    }

    /**
     * Get payment method title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfigData('title') ?? trans('stripe::app.title');
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfigData('description') ?? trans('stripe::app.description');
    }

    /**
     * Get payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        $url = $this->getConfigData('image');

        return $url ? Storage::url($url) : bagisto_asset('images/stripe.png', 'shop');
    }

    /**
     * Get Stripe API key.
     *
     * @return string|null
     */
    public function getApiKey()
    {
        $isSandbox = $this->getConfigData('sandbox');

        return $isSandbox
            ? $this->getConfigData('api_test_key')
            : $this->getConfigData('api_key');
    }

    /**
     * Get Stripe publishable key.
     *
     * @return string|null
     */
    public function getPublishableKey()
    {
        $isSandbox = $this->getConfigData('sandbox');

        return $isSandbox
            ? $this->getConfigData('api_test_publishable_key')
            : $this->getConfigData('api_publishable_key');
    }

    /**
     * Check if required credentials are configured.
     *
     * @return bool
     */
    public function hasValidCredentials()
    {
        $isSandbox = $this->getConfigData('sandbox');

        if ($isSandbox) {
            return $this->getConfigData('api_test_key') && $this->getConfigData('api_test_publishable_key');
        }

        return $this->getConfigData('api_key') && $this->getConfigData('api_publishable_key');
    }

    /**
     * Create Stripe Checkout Session.
     *
     * @return \Stripe\Checkout\Session
     */
    public function createCheckoutSession($cart = null)
    {
        if (! $cart) {
            $cart = Cart::getCart();
        }

        BaseStripe::setApiKey($this->getApiKey());

        $lineItems = $this->prepareLineItems($cart);

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => route('stripe.payment.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => route('stripe.payment.cancel').'?session_id={CHECKOUT_SESSION_ID}',
            'metadata'             => [
                'cart_id' => $cart->id,
            ],
        ]);
    }

    /**
     * Retrieve and validate Stripe checkout session.
     *
     * @param  string  $sessionId
     * @return \Stripe\Checkout\Session|false
     */
    public function retrieveCheckoutSession($sessionId)
    {
        try {
            BaseStripe::setApiKey($this->getApiKey());

            $session = Session::retrieve($sessionId);

            return $session->payment_status === 'paid' ? $session : false;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Prepare line items for Stripe Checkout.
     *
     * @return array
     */
    private function prepareLineItems($cart)
    {
        $lineItems = [];

        foreach ($cart->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => strtolower(core()->getBaseCurrencyCode()),

                    'product_data' => [
                        'name' => $item->product->name,
                    ],

                    'unit_amount' => (int) round($item->base_price * 100),
                ],

                'quantity' => $item->quantity,
            ];
        }

        if ($cart->base_shipping_amount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => strtolower(core()->getBaseCurrencyCode()),

                    'product_data' => [
                        'name' => 'Shipping',
                    ],

                    'unit_amount' => (int) round($cart->base_shipping_amount * 100),
                ],

                'quantity' => 1,
            ];
        }

        if ($cart->base_tax_total > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => strtolower(core()->getBaseCurrencyCode()),

                    'product_data' => [
                        'name' => 'Tax',
                    ],

                    'unit_amount' => (int) round($cart->base_tax_total * 100),
                ],

                'quantity' => 1,
            ];
        }

        return $lineItems;
    }
}
