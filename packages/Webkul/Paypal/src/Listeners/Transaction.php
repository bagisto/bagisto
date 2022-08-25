<?php

namespace Webkul\Paypal\Listeners;

use Webkul\Paypal\Payment\SmartButton;
use Webkul\Sales\Repositories\OrderTransactionRepository;

class Transaction
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Paypal\Payment\SmartButton  $smartButton
     * @param  \Webkul\Sales\Repositories\OrderTransactionRepository  $orderTransactionRepository
     * @return void
     */
    public function __construct(
        protected SmartButton $smartButton,
        protected OrderTransactionRepository $orderTransactionRepository
    )
    {
    }

    /**
     * Save the transaction data for online payment.
     * 
     * @param  \Webkul\Sales\Models\Invoice $invoice
     * @return void
    */
    public function saveTransaction($invoice) {
        $data = request()->all();

        if ($invoice->order->payment->method == 'paypal_smart_button') {
            if (
                isset($data['orderData'])
                && isset($data['orderData']['orderID'])
            ) {
                $smartButtonOrderId = $data['orderData']['orderID'];
                $transactionDetails = $this->smartButton->getOrder($smartButtonOrderId);
                $transactionDetails = json_decode(json_encode($transactionDetails), true);

                if ($transactionDetails['statusCode'] == 200) {
                    $transactionData['transaction_id'] = $transactionDetails['result']['id'];
                    $transactionData['status'] = $transactionDetails['result']['status'];
                    $transactionData['type'] = $transactionDetails['result']['intent'];
                    $transactionData['payment_method'] = $invoice->order->payment->method;
                    $transactionData['order_id'] = $invoice->order->id;
                    $transactionData['invoice_id'] = $invoice->id;
                    $transactionData['data'] = json_encode (
                        array_merge($transactionDetails['result']['purchase_units'],
                        $transactionDetails['result']['payer'])
                    );

                    $this->orderTransactionRepository->create($transactionData);
                }
            }
        } elseif ($invoice->order->payment->method == 'paypal_standard') {
            $transactionData['transaction_id'] = $data['txn_id'];
            $transactionData['status'] = $data['payment_status'];
            $transactionData['type'] = $data['payment_type'];
            $transactionData['payment_method'] = $invoice->order->payment->method;
            $transactionData['order_id'] = $invoice->order->id;
            $transactionData['invoice_id'] = $invoice->id;
            $transactionData['data'] = json_encode ($data);

            $this->orderTransactionRepository->create($transactionData);
        }
    }
}