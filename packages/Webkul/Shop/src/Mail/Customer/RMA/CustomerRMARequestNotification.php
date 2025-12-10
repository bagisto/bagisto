<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerRMARequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public array $customerRmaData) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $adminDetails = core()->getAdminEmailDetails();

        return new Envelope(
            from: new Address($adminDetails['email']),
            to: [new Address($this->customerRmaData['email'])],
            subject: trans('shop::app.rma.customer.create.heading'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.customers.rma.new-rma-request',
            with: [
                'customerRmaData' => $this->customerRmaData,
            ],
        );
    }
}
