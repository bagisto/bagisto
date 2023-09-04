<?php


namespace Webkul\Admin\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CanceledNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function __construct(public $order)
    {
    }

    public function build()
    {
        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($this->order->customer_email, $this->order->customer_full_name)
            ->subject(trans('admin::app.emails.orders.canceled.subject'))
            ->view('admin::emails.orders.canceled');
    }
}