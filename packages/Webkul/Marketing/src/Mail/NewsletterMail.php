<?php

namespace Webkul\Marketing\Mail;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Admin\Mail\BaseMailable;
use Webkul\Marketing\Contracts\Campaign;

class NewsletterMail extends BaseMailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public string $email,
        public Campaign $campaign
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->email),
            ],
            subject: $this->campaign->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->campaign->email_template->content,
        );
    }
}
