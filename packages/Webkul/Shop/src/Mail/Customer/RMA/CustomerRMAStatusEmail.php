<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class CustomerRMAStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public array $rmaStatus) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $senderDetails = core()->getSenderEmailDetails();

        return new Envelope(
            from: new Address($senderDetails['email'], $senderDetails['name']),
            to: [new Address($this->rmaStatus['email'])],
            subject: trans('shop::app.rma.mail.status.title'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.customers.rma.status',
            with: $this->rmaStatus,
        );
    }
}