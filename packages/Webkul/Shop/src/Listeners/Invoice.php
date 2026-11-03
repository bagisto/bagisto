<?php

namespace Webkul\Shop\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Shop\Mail\Customer\InvoiceOverdueReminder;
use Webkul\Shop\Mail\Order\InvoicedNotification;

class Invoice extends Base
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected InvoiceRepository $invoiceRepository) {}

    /**
     * Mail the customer the new invoice, then mark that invoice alone as emailed.
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

            $this->invoiceRepository->update(['email_sent' => true], $invoice->id);
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
