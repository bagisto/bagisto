<?php

namespace Webkul\PreOrder\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Product In Stock Mail class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductInStockNotification extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The order item instance.
     *
     * @var Order
     */
    public $item;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->item = $item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->item->order->customer_email, $this->item->order->customer_full_name)
                ->subject(trans('preorder::app.mail.in-stock.subject'))
                ->view('preorder::emails.in-stock');
    }
}
