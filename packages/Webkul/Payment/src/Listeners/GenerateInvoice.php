<?php
namespace Webkul\Payment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

/**
 * Generate Invoice Event handler
 *
 */
class GenerateInvoice
{
    /**
     * Create the event listener.
     *
     * @param  Webkul\Sales\Repositories\OrderRepository $orderRepository
     * @param \Webkul\Sales\Repositories\InvoiceRepository invoiceRepository
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository
    )
    {
    }

    /**
     * Generate a new invoice.
     *
     * @param  object  $order
     * @return void
     */
    public function handle($order)
    {
        if (
            $order->payment->method == 'cash_on_delivery'
            && core()->getConfigData('sales.paymentmethods.cash_on_delivery.generate_invoice')
        ) {
            $this->invoiceRepository->create(
                $this->prepareInvoiceData($order),
                core()->getConfigData('sales.paymentmethods.cash_on_delivery.invoice_status'),
                core()->getConfigData('sales.paymentmethods.cash_on_delivery.order_status')
            );
        }

        if (
            $order->payment->method == 'money_transfer'
            && core()->getConfigData('sales.paymentmethods.money_transfer.generate_invoice')
        ) {
            $this->invoiceRepository->create(
                $this->prepareInvoiceData($order),
                core()->getConfigData('sales.paymentmethods.money_transfer.invoice_status'),
                core()->getConfigData('sales.paymentmethods.money_transfer.order_status')
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