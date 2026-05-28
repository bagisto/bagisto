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
        $sender = $this->resolveSender();

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

    /**
     * Resolve the sender address — channel override falling back to the
     * global Bagisto sender configuration.
     *
     * @return array{email: string, name: string}
     */
    protected function resolveSender(): array
    {
        $channelCode = $this->withdrawal->channel->code ?? null;

        $email = core()->getConfigData('sales.eu_withdrawal.notifications.sender_email', $channelCode);

        if (empty($email)) {
            return core()->getSenderEmailDetails();
        }

        return [
            'email' => $email,
            'name' => core()->getSenderEmailDetails()['name'],
        ];
    }
}
