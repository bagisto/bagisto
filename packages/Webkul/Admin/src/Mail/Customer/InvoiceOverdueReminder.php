<?php

namespace Webkul\Admin\Mail\Customer;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;

class InvoiceOverdueReminder extends Mailable
{
    /**
     * Create a new message instance.
     * 
     * @return void
     */
    public function __construct(
        public $customer,
        public array $invoice
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    core()->getSenderEmailDetails()['email'],
                    core()->getSenderEmailDetails()['name']
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
            view: 'shop::emails.customer.invoice-reminder',
            with: [
                'customer' => $this->customer,
                'invoice'  => $this->invoice,
            ]
        );
    }
}