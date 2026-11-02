<?php

namespace Webkul\Shop\Listeners;

use Webkul\Sales\Repositories\RefundRepository;
use Webkul\Shop\Mail\Order\RefundedNotification;

class Refund extends Base
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected RefundRepository $refundRepository) {}

    /**
     * Mail the customer the new refund, then mark that refund alone as emailed.
     *
     * @param  \Webkul\Sales\Contracts\Refund  $refund
     * @return void
     */
    public function afterCreated($refund)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_refund')) {
                return;
            }

            $this->prepareMail($refund, new RefundedNotification($refund));

            $this->refundRepository->update(['email_sent' => true], $refund->id);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
