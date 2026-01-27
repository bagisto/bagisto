<?php

namespace Webkul\PayU\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\PayU\Payment\PayU;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Controllers\Controller;

class PayUController extends Controller
{
    /**
     * Payment success status constant.
     */
    public const PAYMENT_SUCCESS = 'success';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected OrderTransactionRepository $orderTransactionRepository,
        protected InvoiceRepository $invoiceRepository,
        protected PayU $payU,
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

        return view('payu::checkout.redirect', [
            'paymentUrl' => $this->payU->getPaymentUrl(),
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
            $cartId = $response['udf1'] ?? null;

            if (! $cartId) {
                session()->flash('error', trans('payu::app.response.invalid-transaction'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $cart = $this->cartRepository->find($cartId);

            if (! $cart || ! $cart->is_active) {
                session()->flash('error', trans('payu::app.response.cart-not-found'));

                return redirect()->route('shop.checkout.cart.index');
            }

            Cart::setCart($cart);

            Cart::collectTotals();

            $data = (new OrderResource($cart))->jsonSerialize();

            $data['payment']['additional'] = [
                'payu_txnid' => $response['txnid'] ?? '',
                'payu_mihpayid' => $response['mihpayid'] ?? '',
                'payu_mode' => $response['mode'] ?? '',
                'payu_status' => $response['status'] ?? '',
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                $this->orderTransactionRepository->create([
                    'transaction_id' => $response['txnid'] ?? '',
                    'status' => self::PAYMENT_SUCCESS,
                    'type' => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $response['amount'] ?? $order->base_grand_total,
                    'data' => json_encode($response),
                ]);
            }

            Cart::deActivateCart();

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
