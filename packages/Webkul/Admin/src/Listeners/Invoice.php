<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Mail\Order\InvoicedNotification;

class Invoice extends Base
{
    /**
     * After order is created
     *
     * @param  \Webkul\Sale\Contracts\Invoice  $invoice
     * @return void
     */
    public function afterCreated($invoice)
    {
        if ($invoice->email_sent) {
            return;
        }

        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_invoice')) {
                return;
            }

            $this->prepareMail($invoice, new InvoicedNotification($invoice, $invoice->email ?? $this->invoice->order->customer_email));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
