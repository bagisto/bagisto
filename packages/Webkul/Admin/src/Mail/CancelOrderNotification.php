<?php


namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancelOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Order
     * 
     */
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->to($this->order->customer_email, $this->order->customer_full_name)
                    ->subject(trans('shop::app.mail.order.cancel.subject'))
                    ->view('shop::emails.sales.order-cancel');
    }
}