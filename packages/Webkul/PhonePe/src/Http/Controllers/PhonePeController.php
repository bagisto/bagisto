<?php

namespace Webkul\PhonePe\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Illuminate\Routing\Controller;
use Webkul\PhonePe\Payment\PhonePe;
use Illuminate\Support\Facades\Cache;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;

class PhonePeController extends Controller
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
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected OrderTransactionRepository $orderTransactionRepository,
        protected CartRepository $cartRepository,
        protected PhonePe $phonePe
    ) {}

    /**
     * Redirect to PhonePe payment gateway.
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        if (! $this->phonePe->hasValidCredentials()) {
            session()->flash('error', trans('phonepe::app.response.provide-credentials'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('phonepe::app.response.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $paymentUrl = $this->phonePe->initiatePayment($cart);

        return redirect()->away($paymentUrl);
    }

    /**
     * Handle payment callback from PhonePe.
     */
    public function callback()
    {
        $merchantOrderId = request()->input('merchantOrderId');

        if (! $merchantOrderId) {
            session()->flash('error', trans('phonepe::app.response.phonepe-payment-reference-missing'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            /**
             * Check payment status with PhonePe API using the merchant order ID received in the callback request.
             */
            $response = $this->phonePe->checkPaymentStatus($merchantOrderId);

            $state = strtoupper($response['data']['state'] ?? '');

            if ($state === 'PENDING') {
                session()->flash('info', trans('phonepe::app.response.phonepe-payment-pending'));
                
                session()->put('phonepe.pending_order_id', $merchantOrderId);

                return redirect()->route('phonepe.cancel', ['merchantOrderId' => $merchantOrderId]);
            }

            if ($state === 'FAILED') {
                session()->flash('warning', trans('phonepe::app.response.phonepe-payment-failed'));

                return redirect()->route('phonepe.cancel', ['merchantOrderId' => $merchantOrderId]);
            }

            /**
             * Retrieve the cart ID from the payment status response's meta information,
             * which was stored during payment initiation. 
             * This cart ID will be used to create the order if the payment was successful.
             */
            $cartId = $response['data']['metaInfo']['udf1'] ?? null;

            if (! $cartId) {
                session()->flash('info', trans('phonepe::app.response.cart-not-found'));

                return redirect()->route('phonepe.cancel', ['merchantOrderId' => $merchantOrderId]);
            }

            $cart = $this->cartRepository->findOrFail($cartId);

            if (! $cart
                || ! $cart->is_active) {
                session()->flash('info', trans('phonepe::app.response.cart-not-found'));

                return redirect()->route('phonepe.cancel', ['merchantOrderId' => $merchantOrderId]);
            }

            Cart::setCart($cart);

            Cart::collectTotals();

            $data = (new OrderResource($cart))->jsonSerialize();

            /**
             * Prepare additional payment data for order creation, including PhonePe transaction details.
             */
            $data['payment']['additional'] = [
                'phonePe_merchant_order_id' => $merchantOrderId ?? '',
                'phonePe_token' => $response['data']['token'] ?? '',
                'phonePe_status' => $state ?? '',
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                /**
                 * Record the order transaction with PhonePe payment details, including transaction ID and status.
                 */
                $this->orderTransactionRepository->create([
                    'transaction_id' => $response['data']['paymentDetails'][0]['transactionId'] ?? '',
                    'status' => self::PAYMENT_SUCCESS,
                    'type' => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $order->base_grand_total,
                    'data' => json_encode($response['raw']),
                ]);
            }

            Cart::deActivateCart();

            session()->flash('order_id', $order->id);

            session()->flash('success', trans('phonepe::app.response.payment-success'));

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('phonepe::app.response.order-creation-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle payment cancellation.
      *
      * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
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
