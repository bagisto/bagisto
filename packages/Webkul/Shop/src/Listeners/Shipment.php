<?php

namespace Webkul\Shop\Listeners;

use Webkul\Sales\Repositories\ShipmentRepository;
use Webkul\Shop\Mail\Order\ShippedNotification;

class Shipment extends Base
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected ShipmentRepository $shipmentRepository) {}

    /**
     * Mail the customer the new shipment, then mark that shipment alone as emailed.
     *
     * @param  \Webkul\Sales\Contracts\Shipment  $shipment
     * @return void
     */
    public function afterCreated($shipment)
    {
        try {
            if (! core()->getConfigData('emails.general.notifications.emails.general.notifications.new_shipment')) {
                return;
            }

            $this->prepareMail($shipment, new ShippedNotification($shipment));

            $this->shipmentRepository->update(['email_sent' => true], $shipment->id);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
