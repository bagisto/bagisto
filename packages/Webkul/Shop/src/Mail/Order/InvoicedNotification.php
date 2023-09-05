<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoicedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Customer\Contracts\Invoice  $invoice
     * @return void
     */
    public function __construct(public $invoice)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->invoice->order->customer_email, $this->invoice->order->customer_full_name)
            ->subject(trans('shop::app.emails.orders.invoiced.subject'))
            ->view('shop::emails.orders.invoiced');
    }
}
