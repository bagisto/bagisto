<?php

namespace Webkul\Admin\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InventorySourceNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  \Webkul\Sales\Contracts\Shipment  $shipment
     * @return void
     */
    public function __construct(public $shipment)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->shipment->order;
        
        $inventory = $this->shipment->inventory_source;

        return $this->from(core()->getSenderEmailDetails()['email'], core()->getSenderEmailDetails()['name'])
            ->to($inventory->contact_email, $inventory->contact_name)
            ->subject(trans('admin::app.emails.orders.inventory-source.subject'))
            ->view('admin::emails.orders.inventory-source');
    }
}
