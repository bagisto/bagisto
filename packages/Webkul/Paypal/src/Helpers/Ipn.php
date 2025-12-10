<?php

namespace Webkul\Paypal\Helpers;

use Illuminate\Support\Facades\Http;
use Webkul\Paypal\Payment\Standard;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;

class Ipn
{
    /**
     * IPN post data.
     *
     * @var array
     */
    protected $post;

    /**
     * Order $order
     *
     * @var \Webkul\Sales\Contracts\Order
     */
    protected $order;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected Standard $paypalStandard,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected OrderTransactionRepository $orderTransactionRepository
    ) {}

    /**
     * This function process the IPN sent from paypal end.
     *
     * @param  array  $post
     * @return null|void|\Exception
     */
    public function processIpn($post)
    {
        $this->post = $post;

        if (! $this->postBack()) {
            return;
        }

        try {
            if (
                isset($this->post['txn_type'])
                && $this->post['txn_type'] !== 'recurring_payment'
            ) {
                $this->order = $this->orderRepository->findOneByField(['cart_id' => $this->post['invoice']]);

                $this->processOrder();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Post back to PayPal to check whether this request is a valid one.
     *
     * @return bool
     */
    protected function postBack()
    {
        $url = $this->paypalStandard->getIPNUrl();

        try {
            $response = Http::asForm()
                ->post($url, ['cmd' => '_notify-validate'] + $this->post);

            return $response->successful() && $response->body() === 'VERIFIED';
        } catch (\Exception $e) {
            report($e);

            return false;
        }
    }

    /**
     * Process order and create invoice.
     *
     * @return void
     */
    protected function processOrder()
    {
        if ($this->post['payment_status'] == 'Completed') {
            if ($this->post['mc_gross'] != $this->order->grand_total) {
                return;
            }

            $this->order = $this->orderRepository->update(['status' => 'processing'], $this->order->id);

            if ($this->order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData());

                $this->orderTransactionRepository->create([
                    'transaction_id' => $this->post['txn_id'],
                    'status'         => $this->post['payment_status'],
                    'type'           => $this->post['payment_type'] ?? 'instant',
                    'payment_method' => $this->order->payment->method,
                    'order_id'       => $this->order->id,
                    'invoice_id'     => $invoice->id,
                    'data'           => json_encode($this->post),
                ]);
            }
        }
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = ['order_id' => $this->order->id];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
