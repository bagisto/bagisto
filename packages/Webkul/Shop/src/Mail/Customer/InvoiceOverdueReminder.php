<?php

namespace Webkul\Shop\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Sales\Contracts\Invoice;
use Webkul\Shop\Mail\Mailable;

class InvoiceOverdueReminder extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(public Invoice $invoice) {}

    /**
     * Get the message envelope, addressed to the customer who placed the invoiced order.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->invoice->order->customer_email,
                    $this->invoice->order->customer_full_name
                ),
            ],
            subject: trans('shop::app.emails.customers.reminder.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.customers.invoice-reminder',
        );
    }
}
