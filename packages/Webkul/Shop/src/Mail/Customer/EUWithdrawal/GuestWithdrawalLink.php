<?php

namespace Webkul\Shop\Mail\Customer\EUWithdrawal;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GuestWithdrawalLink extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new mailable instance.
     */
    public function __construct(
        public string $toEmail,
        public string $signedUrl,
        public string $orderIncrementId,
    ) {}

    /**
     * Build the message envelope.
     */
    public function envelope(): Envelope
    {
        $sender = core()->getSenderEmailDetails();

        return new Envelope(
            from: new Address($sender['email'], $sender['name']),
            to: [new Address($this->toEmail)],
            subject: trans('shop::app.eu_withdrawal.emails.guest_link.subject'),
        );
    }

    /**
     * Build the message content.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.customers.eu-withdrawal.guest-link',
            with: [
                'signedUrl' => $this->signedUrl,
                'orderIncrementId' => $this->orderIncrementId,
            ],
        );
    }
}
