<?php

namespace Webkul\Admin\Mail\Customer\Gdpr;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\Mailable;

class UpdateRequestMail extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public array $adminUpdateRequest) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->adminUpdateRequest['email']),
            ],
            subject: trans('admin::app.emails.gdpr.status'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.customers.gdpr.update-request',
            with: [
                'adminUpdateRequest' => $this->adminUpdateRequest,
            ],
        );
    }
}
