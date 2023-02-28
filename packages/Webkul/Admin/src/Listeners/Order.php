<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Traits\Mails;
use Webkul\Paypal\Payment\SmartButton;

class Order
{
    use Mails;

    public function refundOrder($refund)
    {
        $order = $refund->order;

        if ($order->payment->method === 'paypal_smart_button') {
            /* getting smart button instance */
            $smartButton = new SmartButton;

            /* getting paypal oder id */
            $paypalOrderID = $order->payment->additional['orderID'];

            /* getting capture id by paypal order id */
            $captureID = $smartButton->getCaptureId($paypalOrderID);

            /* now refunding order on the basis of capture id and refund data */
            $smartButton->refundOrder($captureID, [
                'amount' => [
                    'value'         => round($refund->grand_total, 2),
                    'currency_code' => $refund->order_currency_code,
                ],
            ]);
        }
    }
}
