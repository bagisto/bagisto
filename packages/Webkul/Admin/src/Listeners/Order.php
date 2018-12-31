<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\NewOrderNotification;
use Webkul\Admin\Mail\NewInvoiceNotification;
use Webkul\Admin\Mail\NewShipmentNotification;

/**
 * Order event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order {

    /**
     * @param mixed $order
     *
     * Send new order confirmation mail to the customer
     */
    public function sendNewOrderMail($order)
    {
        try {
            Mail::send(new NewOrderNotification($order));
        } catch (\Exception $e) {

        }
    }

    /**
     * @param mixed $invoice
     *
     * Send new invoice mail to the customer
     */
    public function sendNewInvoiceMail($invoice)
    {
        try {
            Mail::send(new NewInvoiceNotification($invoice));
        } catch (\Exception $e) {

        }
    }

    /**
     * @param mixed $shipment
     *
     * Send new shipment mail to the customer
     */
    public function sendNewShipmentMail($shipment)
    {
        try {
            Mail::send(new NewShipmentNotification($shipment));
        } catch (\Exception $e) {

        }
    }

    /**
     * @param mixed $shipment
     *
     * Send new shipment mail to the customer
     */
    public function updateProductInventory($order)
    {
        $productListener = app(\Webkul\Admin\Listeners\Product::class);

        foreach ($order->items as $item) {
            $productListener->afterOrderRecieved($item->product->id, $item->qty_ordered);
        }
    }
}