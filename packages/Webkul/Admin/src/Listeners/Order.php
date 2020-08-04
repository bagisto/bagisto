<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\NewAdminNotification;
use Webkul\Admin\Mail\NewOrderNotification;
use Webkul\Admin\Mail\NewRefundNotification;
use Webkul\Admin\Mail\NewInvoiceNotification;
use Webkul\Admin\Mail\CancelOrderNotification;
use Webkul\Admin\Mail\NewShipmentNotification;
use Webkul\Admin\Mail\OrderCommentNotification;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Admin\Mail\CancelOrderAdminNotification;
use Webkul\Admin\Mail\NewInventorySourceNotification;

class Order
{
    /**
     * InvoiceRepository object
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Create a new repository instance using reflection api.
     *
     * @param  \Webkul\Sales\Repositories\InvoiceRepository
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Send new order Mail to the customer and admin
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function sendNewOrderMail($order)
    {
        try {
            /* email to customer */
            $configKey = 'emails.general.notifications.emails.general.notifications.new-order';
            if (core()->getConfigData($configKey))
                Mail::queue(new NewOrderNotification($order));

            /* email to admin */
            $configKey = 'emails.general.notifications.emails.general.notifications.new-admin';
            if (core()->getConfigData($configKey)) {
                app()->setLocale(env('APP_LOCALE'));
                Mail::queue(new NewAdminNotification($order));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send new invoice mail to the customer
     *
     * @param  \Webkul\Sales\Contracts\Invoice  $invoice
     * @return void
     */
    public function sendNewInvoiceMail($invoice)
    {
        $invoiceLocale = $this->invoiceRepository->getLocaleOfTheInvoice($invoice);

        try {
            if ($invoice->email_sent) {
                return;
            }

            $configKey = 'emails.general.notifications.emails.general.notifications.new-invoice';

            if (core()->getConfigData($configKey)) {
                app()->setLocale($invoiceLocale);
                Mail::queue(new NewInvoiceNotification($invoice));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send new refund mail to the customer
     *
     * @param  \Webkul\Sales\Contracts\Refund  $refund
     * @return void
     */
    public function sendNewRefundMail($refund)
    {
        try {
            $configKey = 'emails.general.notifications.emails.general.notifications.new-refund';

            if (core()->getConfigData($configKey)) {
                Mail::queue(new NewRefundNotification($refund));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Send new shipment mail to the customer
     *
     * @param  \Webkul\Sales\Contracts\Shipment  $shipment
     * @return void
     */
    public function sendNewShipmentMail($shipment)
    {
        try {
            if ($shipment->email_sent) {
                return;
            }

            $configKey = 'emails.general.notifications.emails.general.notifications.new-shipment';

            if (core()->getConfigData($configKey)) {
                Mail::queue(new NewShipmentNotification($shipment));
            }

            $configKey = 'emails.general.notifications.emails.general.notifications.new-inventory-source';

            if (core()->getConfigData($configKey)) {
                Mail::queue(new NewInventorySourceNotification($shipment));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * @param  \Webkul\Sales\Contracts\Order  $order
     * @return void
     */
    public function sendCancelOrderMail($order)
    {
        try {
            /* email to customer */
            $configKey = 'emails.general.notifications.emails.general.notifications.cancel-order';
            if (core()->getConfigData($configKey)) {
                Mail::queue(new CancelOrderNotification($order));
            }

            /* email to admin */
            $configKey = 'emails.general.notifications.emails.general.notifications.new-admin';
            if (core()->getConfigData($configKey)) {
                app()->setLocale(env('APP_LOCALE'));
                Mail::queue(new CancelOrderAdminNotification($order));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * @param  \Webkul\Sales\Contracts\OrderComment  $comment
     * @return void
     */
    public function sendOrderCommentMail($comment)
    {
        if (! $comment->customer_notified) {
            return;
        }

        try {
            Mail::queue(new OrderCommentNotification($comment));
        } catch (\Exception $e) {
            report($e);
        }
    }
}