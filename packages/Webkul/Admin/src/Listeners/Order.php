<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Traits\Mails;
use Webkul\Paypal\Payment\SmartButton;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersAuthorizeRequest;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;

class Order
{
    use Mails;

    public function refundOrder($data)
    {
        $orderID = $data['order']->payment->additional['orderID'];
        $payerID = $data['order']->payment->additional['payerID'];
        // dd($orderID, $payerID, $data['order']->payment->additional);

        $smartButton = new SmartButton();
        $client = $smartButton->client();

        /* get order */
        // $response = $client->execute(new OrdersGetRequest($orderID));
        // dd($response);

        // dd($smartButton->refundOrder($orderID, "{}"));
    }
}
