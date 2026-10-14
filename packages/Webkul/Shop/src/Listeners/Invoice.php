<?php

namespace Webkul\Shop\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Shop\Mail\Customer\InvoiceOverdueReminder;
use Webkul\Shop\Mail\Order\InvoicedNotification;

class Invoice extends Base
{
    /**
     * After an invoice is created.
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

    /**
     * Send the reminder for an invoice that is past its due date.
     *
     * @param  \Webkul\Sales\Contracts\Invoice  $invoice
     * @return void
     */
    public function sendOverdueReminder($invoice)
    {
        try {
            Mail::queue(new InvoiceOverdueReminder($invoice));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
