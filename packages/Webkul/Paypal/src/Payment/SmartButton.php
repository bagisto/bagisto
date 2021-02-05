<?php

namespace Webkul\Paypal\Payment;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;

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
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     *
     * @return PayPalCheckoutSdk\Core\PayPalHttpClient
     */
    public function client()
    {
        return new PayPalHttpClient($this->environment());
    }


    /**
     * Create order for approval of client.
     *
     * @param  array  $body
     * @return HttpResponse
     */
    public function createOrder($body)
    {
        $request = new OrdersCreateRequest;
        $request->prefer('return=representation');
        $request->body = $body;
        return $this->client()->execute($request);
    }

    /**
     * Capture order after approval.
     *
     * @param  string  $orderId
     * @return HttpResponse
     */
    public function captureOrder($orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');
        $this->client()->execute($request);
    }

    /**
     * Get order details.
     *
     * @param  string  $orderId
     * @return HttpResponse
     */
    public function getOrder($orderId)
    {
        return $this->client()->execute(new OrdersGetRequest($orderId));
    }

    /**
     * Get capture id.
     *
     * @param  string  $orderId
     * @return string
     */
    public function getCaptureId($orderId)
    {
        $paypalOrderDetails = $this->getOrder($orderId);
        return $paypalOrderDetails->result->purchase_units[0]->payments->captures[0]->id;
    }

    /**
     * Refund order.
     *
     * @return HttpResponse
     */
    public function refundOrder($captureId, $body = [])
    {
        $request = new CapturesRefundRequest($captureId);
        $request->body = $body;
        return $this->client()->execute($request);
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
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
     *
     * @return PayPalCheckoutSdk\Core\SandboxEnvironment|PayPalCheckoutSdk\Core\ProductionEnvironment
     */
    protected function environment()
    {
        $isSandbox = $this->getConfigData('sandbox') ?: false;

        if ($isSandbox) {
            return new SandboxEnvironment($this->clientId, $this->clientSecret);
        }

        return new ProductionEnvironment($this->clientId, $this->clientSecret);
    }

    /**
     * Initialize properties.
     *
     * @return void
     */
    protected function initialize()
    {
        $this->clientId = $this->getConfigData('client_id') ?: '';

        $this->clientSecret = $this->getConfigData('client_secret') ?: '';
    }
}