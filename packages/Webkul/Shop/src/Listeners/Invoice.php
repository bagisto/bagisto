<?php

namespace Webkul\Shop\Listeners;

use Webkul\Shop\Mail\Order\InvoicedNotification;

class Invoice extends Base
{
    /**
     * After order is created
     *
     * @param  \Webkul\Sales\Contracts\Invoice  $invoice
     * @param  string|null  $duplicateInvoiceEmail
     * @return void
     */
    public function afterCreated($invoice, $duplicateInvoiceEmail = null)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_invoice')) {
                return;
            }

            $this->prepareMail($invoice, new InvoicedNotification($invoice, $duplicateInvoiceEmail));

            $invoice->query()->update(['email_sent' => 1]);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
