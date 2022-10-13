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
            if (isset($data['orderData']['orderID'])) {
                $transactionDetails = $this->smartButton->getOrder($data['orderData']['orderID']);
                
                $transactionDetails = json_decode(json_encode($transactionDetails), true);

                if ($transactionDetails['statusCode'] == 200) {
                    $this->orderTransactionRepository->create([
                        'transaction_id' => $transactionDetails['result']['id'],
                        'status'         => $transactionDetails['result']['status'],
                        'type'           => $transactionDetails['result']['intent'],
                        'payment_method' => $invoice->order->payment->method,
                        'order_id'       => $invoice->order->id,
                        'invoice_id'     => $invoice->id,
                        'data'           => json_encode(
                            array_merge(
                                $transactionDetails['result']['purchase_units'],
                                $transactionDetails['result']['payer']
                            )
                        ),
                    ]);
                }
            }
        } elseif ($invoice->order->payment->method == 'paypal_standard') {
            $this->orderTransactionRepository->create([
                'transaction_id' => $data['txn_id'],
                'status'         => $data['payment_status'],
                'type'           => $data['payment_type'],
                'payment_method' => $invoice->order->payment->method,
                'order_id'       => $invoice->order->id,
                'invoice_id'     => $invoice->id,
                'data'           => json_encode($data),
            ]);
        }
    }
}