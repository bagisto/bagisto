<?php

namespace Webkul\Payment\Listeners;

use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

/**
 * Generate Invoice Event handler
 */
class GenerateInvoice
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository
    ) {}

    /**
     * Generate a new invoice.
     *
     * @param  object  $order
     * @return void
     */
    public function handle($order)
    {
        if (
            $order->payment->method == 'cashondelivery'
            && core()->getConfigData('sales.payment_methods.cashondelivery.generate_invoice')
        ) {
            $this->invoiceRepository->create(
                $this->prepareInvoiceData($order),
                core()->getConfigData('sales.payment_methods.cashondelivery.invoice_status'),
                core()->getConfigData('sales.payment_methods.cashondelivery.order_status')
            );
        }

        if (
            $order->payment->method == 'moneytransfer'
            && core()->getConfigData('sales.payment_methods.moneytransfer.generate_invoice')
        ) {
            $this->invoiceRepository->create(
                $this->prepareInvoiceData($order),
                core()->getConfigData('sales.payment_methods.moneytransfer.invoice_status'),
                core()->getConfigData('sales.payment_methods.moneytransfer.order_status')
            );
        }
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @return array
     */
    protected function prepareInvoiceData($order)
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
