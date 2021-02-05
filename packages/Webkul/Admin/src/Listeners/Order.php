<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Traits\Mails;
use Webkul\Paypal\Payment\SmartButton;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class Order
{
    use Mails;

    public function refundOrder($refund)
    {
        $order = $refund->order;

        if ($order->payment->method === 'paypal_smart_button') {

            try {
                $paypalOrderID = $order->payment->additional['orderID'];

                $smartButton = new SmartButton();
                $client = $smartButton->client();

                /* get order */
                $paypalOrderDetails = $client->execute(new OrdersGetRequest($paypalOrderID));
                $captureID = $paypalOrderDetails->result->purchase_units[0]->payments->captures[0]->id;

                /* refunding order */
                $smartButton->refundOrder($captureID, [
                    'amount' =>
                      [
                        'value' => $refund->grand_total,
                        'currency_code' => $refund->order_currency_code
                      ]
                ]);
            } catch (\Exception $e) {
                /* reporting error */
                report($e);

                /* now aborting whole process */
                abort(500);
            }

        }
    }
}
