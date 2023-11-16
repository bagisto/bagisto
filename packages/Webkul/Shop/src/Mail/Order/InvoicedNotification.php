<?php

namespace Webkul\Shop\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoicedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\Invoice  $invoice
     * @param  string  $receiverEmail
     * @return void
     */
    public function __construct(
        public $invoice,
        public $receiverEmail = ''
    ) {
        /**
         * Model extra properties are lost in the build method so using extra optional
         * properties. If provided second argument then priorty will be given to second argument.
         */
        if (! $receiverEmail) {
            $this->receiverEmail = $invoice->email;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->receiverEmail, $this->invoice->order->customer_full_name)
            ->subject(trans('shop::app.emails.orders.invoiced.subject'))
            ->view('shop::emails.orders.invoiced');
    }
}
