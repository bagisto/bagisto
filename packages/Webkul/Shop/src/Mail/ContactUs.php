<?php

namespace Webkul\Shop\Mail;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactUs extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $contactUs) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    core()->getAdminEmailDetails()['email'],
                    core()->getAdminEmailDetails()['name']
                ),
            ],
            subject: trans('shop::app.emails.contact-us.inquiry-from').' '.$this->contactUs['name'].' '.trans('shop::app.emails.contact-us.contact-from'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.contact-us',
        );
    }
}
