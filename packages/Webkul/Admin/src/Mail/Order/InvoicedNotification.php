<?php

namespace Webkul\Admin\Mail\Order;

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
     * @param  string  $email
     * @return void
     */
    public function __construct(
        public $invoice,
        public $email = null
    )
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
            ->to($this->email ?? $this->invoice->order->customer_email, $this->invoice->order->customer_full_name)
            ->subject(trans('admin::app.emails.orders.invoiced.subject'))
            ->view('admin::emails.orders.invoiced');
    }
}
