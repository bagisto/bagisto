<?php

namespace Webkul\Shop\Listeners;

use Webkul\Sales\Contracts\Order as OrderContract;
use Webkul\Shop\Mail\Order\CanceledNotification;
use Webkul\Shop\Mail\Order\CommentedNotification;
use Webkul\Shop\Mail\Order\CreatedNotification;

class Order extends Base
{
    /**
     * After order is created
     *
     * @return void
     */
    public function afterCreated(OrderContract $order)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_order')) {
                return;
            }

            $this->prepareMail($order, new CreatedNotification($order));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send cancel order mail.
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function afterCanceled($order)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.cancel_order')) {
                return;
            }

            $this->prepareMail($order, new CanceledNotification($order));
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send order comment mail.
     *
     * @param  \Webkul\Sales\Contracts\OrderComment  $comment
     * @return void
     */
    public function afterCommented($comment)
    {
        if (! $comment->customer_notified) {
            return;
        }

        try {
            /**
             * Email to customer.
             */
            $this->prepareMail($comment, new CommentedNotification($comment));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
