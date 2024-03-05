<?php

namespace Webkul\Shop\Listeners;

use Webkul\Shop\Mail\Order\ShippedNotification;

class Shipment extends Base
{
    /**
     * After order is created
     *
     * @param  \Webkul\Sale\Contracts\Shipment  $shipment
     * @return void
     */
    public function afterCreated($shipment)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_shipment')) {
                return;
            }

            $this->prepareMail($shipment, new ShippedNotification($shipment));

            $shipment->query()->update(['email_sent' => 1]);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
