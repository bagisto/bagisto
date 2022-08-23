<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewRefundNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\Refund  $refund
     * @return void
     */
    public function __construct(public $refund)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->refund->order;

        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($order->customer_email, $order->customer_full_name)
            ->subject(trans('shop::app.mail.refund.subject', ['order_id' => $order->increment_id]))
            ->view('shop::emails.sales.new-refund');
    }
}
