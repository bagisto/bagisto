<?php

namespace Webkul\Paypal\Helpers;

use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;

/**
 * Paypal ipn listener helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Ipn
{
    /**
     * Ipn post data
     *
     * @var array
     */
    protected $post;

    /**
     * Order object
     *
     * @var object
     */
    protected $order;

    /**
     * OrderRepository object
     *
     * @var object
     */
    protected $orderRepository;

    /**
     * InvoiceRepository object
     *
     * @var object
     */
    protected $invoiceRepository;

    /**
     * Create a new helper instance.
     *
     * @param  Webkul\Sales\Repositories\OrderRepository   $orderRepository
     * @param  Webkul\Sales\Repositories\InvoiceRepository $invoiceRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * This function process the ipn sent from paypal end
     *
     * @param array $post
     * @return void
     */
    public function processIpn($post)
    {
        $this->post = $post;

        if (! $this->postBack())
            return;

        try {
            if (isset($this->post['txn_type']) && 'recurring_payment' == $this->post['txn_type']) {

            } else {
                $this->getOrder();

                $this->processOrder();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Load order via ipn invoice id
     *
     *
     * @return void
     */
    protected function getOrder()
    {
        if (empty($this->order)) {
            $this->order = $this->orderRepository->findOneByField(['cart_id' => $this->post['invoice']]);
        }
    }

    /**
     * Process order and create invoice
     *
     *
     * @return void
     */
    protected function processOrder()
    {
        if ($this->post['payment_status'] == 'completed') {
            if ($this->post['mc_gross'] != $this->order->grand_total) {
                
            } else {
                $this->orderRepository->update(['status' => 'processing'], $this->order->id);

                if ($this->order->canInvoice()) {
                    $this->invoiceRepository->create($this->prepareInvoiceData());
                }
            }
        }
    }

    /**
     * Prepares order's invoice data for creation
     *
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = [
            "order_id" => $this->order->id
        ];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Post back to PayPal to check whether this request is a valid one
     *
     * @param Zend_Http_Client_Adapter_Interface $httpAdapter
     */
    protected function postBack()
    {
        if (array_key_exists('test_ipn', $this->post) && 1 === (int) $this->post['test_ipn'])
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        else
            $url = 'https://www.paypal.com/cgi-bin/webscr';

        // Set up request to PayPal
        $request = curl_init();
        curl_setopt_array($request, array
        (
            CURLOPT_URL => $url,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => http_build_query(array('cmd' => '_notify-validate') + $this->post),
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER => FALSE,
        ));

        // Execute request and get response and status code
        $response = curl_exec($request);
        $status   = curl_getinfo($request, CURLINFO_HTTP_CODE);

        // Close connection
        curl_close($request);

        if ($status == 200 && $response == 'VERIFIED') {
            return true;
        }

        return false;
    }
}