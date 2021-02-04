<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Traits\Mails;
use Webkul\Paypal\Payment\SmartButton;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class Order
{
    use Mails;

    public function refundOrder($data)
    {
        $paymentMethod = $data['order']->payment->method;

        if ($paymentMethod === 'paypal_smart_button') {

            try {
                $orderID = $data['order']->payment->additional['orderID'];

                $smartButton = new SmartButton();
                $client = $smartButton->client();

                /* get order */
                $orderDetails = $client->execute(new OrdersGetRequest($orderID));
                $captureID = $orderDetails->result->purchase_units[0]->payments->captures[0]->id;

                /* refunding order */
                $response = $smartButton->refundOrder($captureID, "{}");
            } catch (\Exception $e) {
                /* reporting error */
                report($e);

                /* now aborting whole process */
                abort(500);
            }
        }
    }
}
