<?php

namespace Webkul\Shop\Mail\Customer\RMA;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\RMA\Contracts\RMA;

class CustomerRMAStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public RMA $rma) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                core()->getSenderEmailDetails()['email'],
                core()->getSenderEmailDetails()['name']
            ),
            to: [new Address(
                $this->rma->order->customer->email,
                $this->rma->order->customer->name
            )],
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
        );
    }
}
