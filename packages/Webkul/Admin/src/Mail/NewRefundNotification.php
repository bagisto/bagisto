<?php

namespace Webkul\Admin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * New Refund Mail class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewRefundNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The refund instance.
     *
     * @var Refund
     */
    public $refund;

    /**
     * Create a new message instance.
     *
     * @param mixed $refund
     * @return void
     */
    public function __construct($refund)
    {
        $this->refund = $refund;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->refund->order;

        return $this->to($order->customer_email, $order->customer_full_name)
                ->from(env('SHOP_MAIL_FROM'))
                ->subject(trans('shop::app.mail.refund.subject', ['order_id' => $order->increment_id]))
                ->view('shop::emails.sales.new-refund');
    }
}
