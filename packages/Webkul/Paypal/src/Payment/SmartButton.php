<?php

namespace Webkul\Paypal\Payment;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class SmartButton extends Paypal
{
    /**
     * Client ID.
     *
     * @var string
     */
    protected $clientId;

    /**
     * Client secret.
     *
     * @var string
     */
    protected $clientSecret;

    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code  = 'paypal_smart_button';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Return paypal redirect url
     *
     * @return string
     */
    public function getRedirectUrl()
    {
    }

    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public function client()
    {
        return new PayPalHttpClient($this->environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
     */
    protected function environment()
    {
        $isSandbox = core()->getConfigData('sales.paymentmethods.paypal_smart_button.sandbox') ?: false;

        if ($isSandbox) {
            return new SandboxEnvironment($this->clientId, $this->clientSecret);
        }

        return new ProductionEnvironment($this->clientId, $this->clientSecret);
    }

    /**
     * Initialize properties.
     */
    protected function initialize()
    {
        $this->clientId = core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_id') ?: '';

        $this->clientSecret = core()->getConfigData('sales.paymentmethods.paypal_smart_button.client_secret') ?: '';
    }
}