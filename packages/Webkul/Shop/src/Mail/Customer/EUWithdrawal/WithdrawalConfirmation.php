<?php

namespace Webkul\Shop\Mail\Customer\EUWithdrawal;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\EUWithdrawal\Contracts\Withdrawal as WithdrawalContract;

class WithdrawalConfirmation extends Mailable
{
    use SerializesModels;

    /**
     * Create a new mailable instance.
     */
    public function __construct(public WithdrawalContract $withdrawal) {}

    /**
     * Build the message envelope.
     */
    public function envelope(): Envelope
    {
        $sender = core()->getSenderEmailDetails();

        return new Envelope(
            from: new Address($sender['email'], $sender['name']),
            to: [new Address($this->withdrawal->customer_email)],
            subject: trans('shop::app.eu_withdrawal.emails.confirmation.subject', [
                'order_id' => $this->withdrawal->order->increment_id ?? $this->withdrawal->order_id,
            ]),
        );
    }

    /**
     * Build the message content.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.customers.eu-withdrawal.confirmation',
            with: ['withdrawal' => $this->withdrawal],
        );
    }
}
