<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Mail\Order\InventorySourceNotification;
use Webkul\Admin\Mail\Order\ShippedNotification;
use Webkul\Sales\Contracts\Shipment as ShipmentContract;

class Shipment extends Base
{
    /**
     * After order is created
     *
     * @return void
     */
    public function afterCreated(ShipmentContract $shipment)
    {
        try {
            $sendShipmentNotification = core()->getConfigData('emails.general.notifications.emails.general.notifications.new_shipment_mail_to_admin');
            
            $sendInventoryNotification = core()->getConfigData('emails.general.notifications.emails.general.notifications.new_inventory_source');

            if (! $sendShipmentNotification && ! $sendInventoryNotification) {
                return;
            }

            if ($sendShipmentNotification) {
                $this->prepareMail($shipment, new ShippedNotification($shipment));
            }
        
            if ($sendInventoryNotification) {
                $this->prepareMail($shipment, new InventorySourceNotification($shipment));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}
