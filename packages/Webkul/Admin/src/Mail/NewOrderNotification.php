<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * New Order Mail class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var  \Webkul\Sales\Contracts\Order  $order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->order->customer_email, $this->order->customer_full_name)
                    ->subject(trans('shop::app.mail.order.subject'))
                    ->view('shop::emails.sales.new-order');
    }
}
