<?php

namespace Webkul\Paypal\Payment;

use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;

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
     * PayPal SDK Client.
     *
     * @var \PaypalServerSdkLib\PaypalServerSdkClient
     */
    protected $client;

    /**
     * Payment method code.
     *
     * @var string
     */
    protected $code = 'paypal_smart_button';

    /**
     * Paypal partner attribution id.
     *
     * @var string
     */
    protected $paypalPartnerAttributionId = 'Bagisto_Cart';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Returns PayPal SDK client instance.
     *
     * @return \PaypalServerSdkLib\PaypalServerSdkClient
     */
    public function getClient()
    {
        if (! $this->client) {
            $this->client = $this->buildClient();
        }

        return $this->client;
    }

    /**
     * Build PayPal SDK client.
     *
     * @return \PaypalServerSdkLib\PaypalServerSdkClient
     */
    protected function buildClient()
    {
        $isSandbox = $this->getConfigData('sandbox') ?: false;

        $environment = $isSandbox
            ? Environment::SANDBOX
            : Environment::PRODUCTION;

        return PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init(
                    $this->clientId,
                    $this->clientSecret
                )
            )
            ->environment($environment)
            ->build();
    }

    /**
     * Create order for approval of client.
     *
     * @param  array  $body
     * @return mixed
     */
    public function createOrder($body)
    {
        $ordersController = $this->getClient()->getOrdersController();

        $options = [
            'body'                       => $body,
            'paypalPartnerAttributionId' => $this->paypalPartnerAttributionId,
            'prefer'                     => 'return=representation',
        ];

        $response = $ordersController->createOrder($options);

        return $response->getResult();
    }

    /**
     * Capture order after approval.
     *
     * @param  string  $orderId
     * @return mixed
     */
    public function captureOrder($orderId)
    {
        $ordersController = $this->getClient()->getOrdersController();

        $options = [
            'id'                         => $orderId,
            'paypalPartnerAttributionId' => $this->paypalPartnerAttributionId,
            'prefer'                     => 'return=representation',
        ];

        $response = $ordersController->captureOrder($options);

        return $response->getResult();
    }

    /**
     * Get order details.
     *
     * @param  string  $orderId
     * @return mixed
     */
    public function getOrder($orderId)
    {
        $ordersController = $this->getClient()->getOrdersController();

        $response = $ordersController->getOrder(['id' => $orderId]);

        return $response->getResult();
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

        return $paypalOrderDetails->getPurchaseUnits()[0]->getPayments()->getCaptures()[0]->getId();
    }

    /**
     * Refund order.
     *
     * @param  string  $captureId
     * @param  array  $body
     * @return mixed
     */
    public function refundOrder($captureId, $body = [])
    {
        $paymentsController = $this->getClient()->getPaymentsController();

        $options = [
            'captureId'                  => $captureId,
            'paypalPartnerAttributionId' => $this->paypalPartnerAttributionId,
            'prefer'                     => 'return=representation',
        ];

        if (! empty($body)) {
            $options['body'] = $body;
        }

        $response = $paymentsController->refundCapturedPayment($options);

        return $response->getResult();
    }

    /**
     * Return paypal redirect url.
     *
     * @return string
     */
    public function getRedirectUrl() {}

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
