<?php

namespace Webkul\Stripe\Payment;

use Illuminate\Support\Facades\Storage;
use Stripe\Checkout\Session;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe as BaseStripe;
use Stripe\Webhook;
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
        return parent::isAvailable()
            && $this->hasValidCredentials();
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
            return $this->getConfigData('api_test_key')
                && $this->getConfigData('api_test_publishable_key');
        }

        return $this->getConfigData('api_key')
            && $this->getConfigData('api_publishable_key');
    }

    /**
     * Create Stripe Checkout Session.
     *
     * @return Session
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
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.payment.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.payment.cancel').'?session_id={CHECKOUT_SESSION_ID}',
            'metadata' => [
                'cart_id' => $cart->id,
                'base_grand_total' => (string) $cart->base_grand_total,
            ],
        ]);
    }

    /**
     * Retrieve and validate Stripe checkout session.
     *
     * @param  string  $sessionId
     * @return Session|false
     */
    public function retrieveCheckoutSession($sessionId)
    {
        try {
            BaseStripe::setApiKey($this->getApiKey());

            $session = Session::retrieve($sessionId);

            return $session->payment_status === 'paid' ? $session : false;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * Find the checkout session a payment intent was taken through, or nothing when no checkout took it.
     */
    public function findCheckoutSession(string $paymentIntentId): ?Session
    {
        BaseStripe::setApiKey($this->getApiKey());

        return Session::all([
            'payment_intent' => $paymentIntentId,
            'limit' => 1,
        ])->first();
    }

    /**
     * Get the signing secret of the webhook endpoint for the configured mode.
     */
    public function getWebhookSecret(): ?string
    {
        return $this->getConfigData('sandbox')
            ? $this->getConfigData('webhook_test_secret')
            : $this->getConfigData('webhook_secret');
    }

    /**
     * Read a webhook event, or nothing when no secret is set or Stripe did not sign it with that secret.
     */
    public function constructWebhookEvent(string $payload, ?string $signature): ?Event
    {
        $secret = $this->getWebhookSecret();

        if (
            empty($secret)
            || empty($signature)
        ) {
            return null;
        }

        try {
            return Webhook::constructEvent($payload, $signature, $secret);
        } catch (\UnexpectedValueException|SignatureVerificationException) {
            return null;
        }
    }

    /**
     * Convert an amount in a currency's smallest unit, as Stripe reports it, back to the currency itself.
     */
    public function fromStripeAmount(int $amount, string $currencyCode): float
    {
        $currency = core()->getAllCurrencies()->firstWhere('code', strtoupper($currencyCode));

        return $amount / (10 ** ($currency?->decimal ?? 2));
    }

    /**
     * Convert an amount to the base currency's smallest unit, as Stripe charges it; a zero-decimal currency is unchanged.
     *
     * @param  float  $amount
     * @return int
     */
    private function formatAmount($amount)
    {
        $decimal = core()->getBaseCurrency()->decimal ?? 2;

        return (int) round($amount * (10 ** $decimal));
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
                    'currency' => strtolower(core()->getBaseCurrencyCode()),

                    'product_data' => [
                        'name' => $item->product->name,
                    ],

                    'unit_amount' => $this->formatAmount($item->base_price),
                ],

                'quantity' => $item->quantity,
            ];
        }

        if ($cart->base_shipping_amount > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower(core()->getBaseCurrencyCode()),

                    'product_data' => [
                        'name' => trans('stripe::app.line-items.shipping'),
                    ],

                    'unit_amount' => $this->formatAmount($cart->base_shipping_amount),
                ],

                'quantity' => 1,
            ];
        }

        if ($cart->base_tax_total > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => strtolower(core()->getBaseCurrencyCode()),

                    'product_data' => [
                        'name' => trans('stripe::app.line-items.tax'),
                    ],

                    'unit_amount' => $this->formatAmount($cart->base_tax_total),
                ],

                'quantity' => 1,
            ];
        }

        return $lineItems;
    }
}
