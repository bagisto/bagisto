<?php

namespace Webkul\Paypal;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
     */
    public static function environment()
    {
        $clientId = "Acr1C3OWYENmPT1ac0SpNvr5AFR_gMXI3pzcMnQzjV9ZW5E4M0A2teSUaipet384AotalP8KI-gwzbD0";
        $clientSecret = "EMeh5Ai8A216ygPrs5AfKty_DyY3RMDBAemgqnrWkgYi5O9tq5chXI-PXsldYtO5Do4pEYYeqB66Hsdw";
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}