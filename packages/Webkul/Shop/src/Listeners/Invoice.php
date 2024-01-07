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
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_invoice')) {
                return;
            }

            $this->prepareMail($invoice, new InvoicedNotification($invoice));

            $invoice->query()->update(['email_sent' => 1]);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
