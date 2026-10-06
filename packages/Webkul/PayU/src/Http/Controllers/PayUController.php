<?php

namespace Webkul\PayU\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\PayU\Payment\PayU;
use Webkul\Sales\Contracts\Order as OrderContract;
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
     * @return View
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

        $currency = strtoupper($cart->base_currency_code ?? core()->getBaseCurrencyCode());

        if (! $this->payU->isCurrencySupported($currency)) {
            session()->flash('error', trans('payu::app.response.supported-currency-error', [
                'currency' => $currency,
                'supportedCurrencies' => implode(', ', $this->payU->getSupportedCurrencies()),
            ]));

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
     * @return Response
     */
    public function success()
    {
        $response = request()->all();

        if (! $this->payU->verifyHash($response)) {
            session()->flash('error', trans('payu::app.response.hash-mismatch'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            if (! ($response['udf1'] ?? null)) {
                session()->flash('error', trans('payu::app.response.invalid-transaction'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $order = $this->placeOrder($response);

            if (! $order) {
                session()->flash('error', $this->orderFailureMessage((int) $response['udf1']));

                return redirect()->route('shop.checkout.cart.index');
            }

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
     * @return Response
     */
    public function failure()
    {
        session()->flash('error', trans('payu::app.response.payment-failed'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle payment cancel callback.
     *
     * @return Response
     */
    public function cancel()
    {
        session()->flash('warning', trans('payu::app.response.payment-cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Place the order from PayU's server to server notification, for a payment whose customer
     * never came back to the store, answering with an error only when PayU should send it again.
     */
    public function webhook(): JsonResponse
    {
        $response = request()->all();

        if (! $this->payU->verifyHash($response)) {
            return response()->json(['status' => 'invalid_hash'], 400);
        }

        if (! ($response['udf1'] ?? null)) {
            return response()->json(['status' => 'invalid_transaction'], 400);
        }

        try {
            $order = $this->placeOrder($response);
        } catch (\Exception $e) {
            report($e);

            return response()->json(['status' => 'error'], 500);
        }

        return response()->json(['status' => $order ? 'order_placed' : 'order_not_placed']);
    }

    /**
     * The message for a payment that did not become an order, naming a cart that is gone
     * apart from one that no longer matches what was paid.
     */
    protected function orderFailureMessage(int $cartId): string
    {
        $cart = $this->cartRepository->find($cartId);

        if (
            $cart
            && $cart->is_active
        ) {
            return trans('payu::app.response.invalid-transaction');
        }

        return trans('payu::app.response.cart-not-found');
    }

    /**
     * Turn a paid transaction into an order once, however many times the customer and PayU report it.
     */
    protected function placeOrder(array $response): ?OrderContract
    {
        $cartId = (int) $response['udf1'];

        return Cache::lock('payu.order.'.$cartId, 30)->block(10, function () use ($cartId, $response) {
            if ($order = $this->orderRepository->findOneWhere(['cart_id' => $cartId])) {
                return $order;
            }

            $cart = $this->cartRepository->find($cartId);

            if (
                ! $cart
                || ! $cart->is_active
            ) {
                return null;
            }

            Cart::setCart($cart);

            core()->setCurrentCurrency($cart->cart_currency_code);

            Cart::collectTotals();

            $cart = Cart::getCart();

            if (
                ! $cart
                || Cart::hasError()
                || ! $this->paymentCoversCart($response, $cart)
            ) {
                return null;
            }

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

            return $order;
        });
    }

    /**
     * Whether PayU reports this response as paid, for this cart's amount, and has not been used before.
     */
    protected function paymentCoversCart(array $response, $cart): bool
    {
        if (($response['status'] ?? '') !== self::PAYMENT_SUCCESS) {
            return false;
        }

        $transactionId = $response['txnid'] ?? '';

        if (
            ! $transactionId
            || $this->orderTransactionRepository->findWhere(['transaction_id' => $transactionId])->isNotEmpty()
        ) {
            return false;
        }

        return round((float) ($response['amount'] ?? 0), 2) === round((float) $cart->base_grand_total, 2);
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
