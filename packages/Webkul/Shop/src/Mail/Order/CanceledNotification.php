<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Webkul\Shop\Mail\Mailable;

class CanceledNotification extends Mailable
{
    /**
     * Create a new CanceledNotification instance.
     *
     * @return void
     */
    public function __construct(public $order)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->order->customer_email, $this->order->customer_full_name),
            ],
            subject: trans('admin::app.emails.orders.canceled.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'shop::emails.orders.canceled',
        );
    }
}
