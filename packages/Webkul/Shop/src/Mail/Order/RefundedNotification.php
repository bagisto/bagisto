<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Webkul\Sales\Contracts\Refund;
use Webkul\Shop\Mail\BaseMailable;

class RefundedNotification extends BaseMailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Refund $refund)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address(
                    $this->refund->order->customer_email,
                    $this->refund->order->customer_full_name
                ),
            ],
            subject: trans('shop::app.emails.orders.refunded.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.orders.refunded',
        );
    }
}
