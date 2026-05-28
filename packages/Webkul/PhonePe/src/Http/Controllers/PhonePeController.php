<?php

namespace Webkul\PhonePe\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\PhonePe\Payment\PhonePe;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;

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
     * @return View
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

        if (! $paymentUrl) {
            session()->flash('error', trans('phonepe::app.response.phonepe-payment-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

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

            $cart = $this->cartRepository->find($cartId);

            if (! $cart) {
                session()->flash('info', trans('phonepe::app.response.cart-not-found'));

                return redirect()->route('phonepe.cancel', ['merchantOrderId' => $merchantOrderId]);
            }

            /**
             * Check if an order already exists for this cart (e.g., created by the callback).
             */
            $existingOrder = $this->orderRepository->findOneWhere(['cart_id' => $cartId]);

            if (! $existingOrder) {
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
            }

            /**
             * Store the order ID in the session for the success page.
             */
            session()->flash('order_id', $existingOrder?->id);

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
     * @return RedirectResponse
     */
    public function cancel()
    {
        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle incoming webhook notifications from PhonePe.
     *
     * PhonePe POSTs a JSON payload to this endpoint after every payment event.
     * The raw payload is logged so it can be debugged at any time.
     *
     * @return JsonResponse
     */
    public function webhook()
    {
        $payload = request()->all();

        try {
            $merchantOrderId = $payload['payload']['merchantOrderId'] ?? null;

            $state = strtoupper($payload['payload']['state'] ?? '');

            /**
             * Only process CHECKOUT_ORDER_COMPLETED events (or a SUCCESS state).
             * All other events (PENDING, FAILED, etc.) are acknowledged but ignored.
             */
            if (! $merchantOrderId || ! in_array($state, ['CHECKOUT_ORDER_COMPLETED', 'COMPLETED', 'SUCCESS'], true)) {
                return response()->json(['status' => 'skipped'], 200);
            }

            /**
             * Verify the actual payment status via PhonePe API before acting on
             * the webhook to protect against forged / replayed callbacks.
             */
            $response = $this->phonePe->checkPaymentStatus($merchantOrderId);

            $verifiedState = strtoupper($response['data']['state'] ?? '');

            if (! in_array($verifiedState, ['COMPLETED', 'SUCCESS'], true)) {
                return response()->json(['status' => 'payment_not_confirmed'], 200);
            }

            /**
             * Retrieve cart ID stored in metaInfo.udf1 during payment initiation.
             */
            $cartId = $response['data']['metaInfo']['udf1'] ?? null;

            if (! $cartId) {
                return response()->json(['status' => 'cart_not_found'], 200);
            }

            $cart = $this->cartRepository->find($cartId);

            if (! $cart || ! $cart->is_active) {
                return response()->json(['status' => 'cart_inactive'], 200);
            }

            /**
             * Check if an order already exists for this cart (e.g., created by the callback).
             */
            $existingOrder = $this->orderRepository->findOneWhere(['cart_id' => $cartId]);

            if ($existingOrder) {
                return response()->json(['status' => 'order_already_exists', 'order_id' => $existingOrder->id], 200);
            }

            Cart::setCart($cart);

            Cart::collectTotals();

            $data = (new OrderResource($cart))->jsonSerialize();

            $data['payment']['additional'] = [
                'phonePe_merchant_order_id' => $merchantOrderId,
                'phonePe_token' => $response['data']['token'] ?? '',
                'phonePe_status' => $verifiedState,
                'phonePe_source' => 'webhook',
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

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
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'status' => 'error',
            ], 500);
        }
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
