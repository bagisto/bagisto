<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Mail\Order\RefundedNotification;
use Webkul\Paypal\Payment\SmartButton;

class Refund extends Base
{
    /**
     * After refund is created.
     *
     * @param  \Webkul\Sales\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreated($refund)
    {
        $this->refundOrder($refund);

        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_refund_mail_to_admin')) {
                return;
            }

            $this->prepareMail($refund, new RefundedNotification($refund));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Refund the order through PayPal when the Smart Button method was used.
     *
     * @param  \Webkul\Sales\Contracts\Refund  $refund
     * @return void
     */
    public function refundOrder($refund)
    {
        $order = $refund->order;

        if ($order->payment->method !== 'paypal_smart_button') {
            return;
        }

        $paypalOrderID = $order->payment->additional['orderID'] ?? null;

        if (! $paypalOrderID) {
            return;
        }

        $smartButton = new SmartButton;

        $captureID = $smartButton->getCaptureId($paypalOrderID);

        $smartButton->refundOrder($captureID, [
            'amount' => [
                'value' => round($refund->grand_total, 2),
                'currency_code' => $refund->order_currency_code,
            ],
        ]);
    }
}
