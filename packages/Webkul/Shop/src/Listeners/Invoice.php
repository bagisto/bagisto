<?php

namespace Webkul\Shop\Listeners;

use Webkul\Shop\Mail\Order\InvoicedNotification;

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

            $this->prepareMail($invoice, new InvoicedNotification($invoice));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
