<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Mail\Order\ShippedNotification;
use Webkul\Admin\Mail\Order\InventorySourceNotification;

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
        if ($shipment->email_sent) {
            return;
        }

        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_shipment')) {
                return;
            }

            $this->prepareMail($shipment, new ShippedNotification($shipment));


            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_inventory_source')) {
                return;
            }

            $this->prepareMail($shipment, new InventorySourceNotification($shipment));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
