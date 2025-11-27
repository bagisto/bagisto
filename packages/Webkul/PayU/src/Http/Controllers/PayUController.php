<?php

namespace Webkul\PayU\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\PayU\Enums\TransactionStatus;
use Webkul\PayU\Payment\PayU;
use Webkul\PayU\Repositories\PayUTransactionRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Controllers\Controller;

class PayUController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected PayU $payU,
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected OrderTransactionRepository $orderTransactionRepository,
        protected PayUTransactionRepository $payUTransactionRepository
    ) {}

    /**
     * Redirect to PayU payment gateway.
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        if (! $this->payU->hasValidCredentials()) {
            session()->flash('error', trans('payu::app.response.provide-credentials'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('payu::app.response.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $paymentData = $this->payU->getPaymentData($cart);

        $this->payUTransactionRepository->create([
            'transaction_id' => $paymentData['txnid'],
            'cart_id'        => $cart->id,
            'customer_id'    => $cart->customer_id,
            'amount'         => $paymentData['amount'],
            'status'         => TransactionStatus::PENDING->value,
        ]);

        return view('payu::checkout.redirect', [
            'paymentUrl'  => $this->payU->getPaymentUrl(),
            'paymentData' => $paymentData,
        ]);
    }

    /**
     * Handle payment success callback.
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        $response = request()->all();

        if (! $this->payU->verifyHash($response)) {
            session()->flash('error', trans('payu::app.response.hash-mismatch'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            $transaction = $this->payUTransactionRepository->findOneWhere([
                'transaction_id' => $response['txnid'] ?? '',
            ]);

            if (! $transaction) {
                session()->flash('error', trans('payu::app.response.invalid-transaction'));

                return redirect()->route('shop.checkout.cart.index');
            }

            if ($transaction->status === TransactionStatus::SUCCESS) {
                session()->flash('warning', trans('payu::app.response.payment-already-processed'));

                return redirect()->route('shop.checkout.onepage.success');
            }

            $cart = $this->cartRepository->find($transaction->cart_id);

            if (! $cart || ! $cart->is_active) {
                session()->flash('error', trans('payu::app.response.cart-not-found'));

                return redirect()->route('shop.checkout.cart.index');
            }

            Cart::setCart($cart);

            Cart::collectTotals();

            $data = (new OrderResource($cart))->jsonSerialize();

            $data['payment']['additional'] = [
                'payu_txnid'     => $response['txnid'] ?? '',
                'payu_mihpayid'  => $response['mihpayid'] ?? '',
                'payu_mode'      => $response['mode'] ?? '',
                'payu_status'    => $response['status'] ?? '',
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                $this->orderTransactionRepository->create([
                    'transaction_id' => $response['txnid'] ?? '',
                    'status'         => TransactionStatus::SUCCESS->value,
                    'type'           => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id'       => $order->id,
                    'invoice_id'     => $invoice->id,
                    'amount'         => $transaction->amount,
                    'data'           => json_encode($response),
                ]);
            }

            Cart::deActivateCart();

            $this->payUTransactionRepository->update([
                'status'   => TransactionStatus::SUCCESS->value,
                'response' => $response,
            ], $transaction->id);

            session()->flash('order_id', $order->id);

            session()->flash('success', trans('payu::app.response.payment-success'));

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('payu::app.response.order-creation-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle payment failure callback.
     *
     * @return \Illuminate\Http\Response
     */
    public function failure()
    {
        $response = request()->all();

        if ($txnid = $response['txnid'] ?? null) {
            $transaction = $this->payUTransactionRepository->findOneWhere([
                'transaction_id' => $txnid,
            ]);

            if ($transaction) {
                $this->payUTransactionRepository->update([
                    'status'   => TransactionStatus::FAILED->value,
                    'response' => $response,
                ], $transaction->id);
            }
        }

        session()->flash('error', trans('payu::app.response.payment-failed'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle payment cancel callback.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        $response = request()->all();

        if ($txnid = $response['txnid'] ?? null) {
            $transaction = $this->payUTransactionRepository->findOneWhere([
                'transaction_id' => $txnid,
            ]);

            if ($transaction) {
                $this->payUTransactionRepository->update([
                    'status'   => TransactionStatus::CANCELLED->value,
                    'response' => $response,
                ], $transaction->id);
            }
        }

        session()->flash('warning', trans('payu::app.response.payment-cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Prepare invoice data.
     *
     * @param  object  $order
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
