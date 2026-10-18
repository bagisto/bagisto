<?php

namespace Webkul\Cashfree\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Cashfree\Payment\Cashfree;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;

class CashfreeController extends Controller
{
    /**
     * Payment success status constant.
     */
    public const PAYMENT_SUCCESS = 'success';

    /**
     * Create a new controller instance.
     *
     * @return  void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected OrderTransactionRepository $orderTransactionRepository,
        protected CartRepository $cartRepository,
        protected Cashfree $cashfree
    ) {}

    /**
     * Redirect to Cashfree payment gateway.
     *
     * @return  View|RedirectResponse
     */
    public function redirect()
    {
        if (! $this->cashfree->hasValidCredentials()) {
            session()->flash('error', trans('cashfree::app.response.provide-credentials'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('cashfree::app.response.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $currency = strtoupper($cart->base_currency_code ?? core()->getBaseCurrencyCode());

        if (! $this->cashfree->isCurrencySupported($currency)) {
            session()->flash('error', trans('cashfree::app.response.supported-currency-error', [
                'currency'            => $currency,
                'supportedCurrencies' => implode(', ', $this->cashfree->getSupportedCurrencies()),
            ]));

            return redirect()->route('shop.checkout.cart.index');
        }

        $orderResponse = $this->cashfree->createOrder($cart);

        if (! $orderResponse || empty($orderResponse['payment_session_id'])) {
            session()->flash('error', trans('cashfree::app.response.payment-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        return view('cashfree::redirect', [
            'paymentSessionId' => $orderResponse['payment_session_id'],
            'orderId'          => $orderResponse['order_id'] ?? '',
            'sandbox'          => $this->cashfree->isSandbox(),
        ]);
    }

    /**
     * Handle payment callback from Cashfree.
     *
     * @return  RedirectResponse
     */
    public function callback()
    {
        $orderId = request()->query('order_id') ?? request()->input('order_id');

        if (! $orderId) {
            session()->flash('error', trans('cashfree::app.response.payment-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            $cfOrder = $this->cashfree->getOrder($orderId);

            if (! $cfOrder) {
                session()->flash('error', trans('cashfree::app.response.payment-failed'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $orderStatus = strtoupper($cfOrder['order_status'] ?? '');

            if ($orderStatus !== 'PAID') {
                session()->flash('warning', trans('cashfree::app.response.payment-cancelled'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $cartId = null;

            if (preg_match('/^CF_(\d+)_/', $orderId, $matches)) {
                $cartId = (int) $matches[1];
            }

            $cart = $cartId ? $this->cartRepository->find($cartId) : Cart::getCart();

            if (! $cart) {
                session()->flash('info', trans('cashfree::app.response.cart-not-found'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $existingOrder = $this->orderRepository->findOneWhere(['cart_id' => $cart->id]);

            if ($existingOrder) {
                session()->flash('order_id', $existingOrder->id);
                session()->flash('success', trans('cashfree::app.response.payment-success'));

                return redirect()->route('shop.checkout.onepage.success');
            }

            Cart::setCart($cart);
            Cart::collectTotals();
            $cart = Cart::getCart();

            if (
                Cart::hasError()
                || ! $this->paidAmountCoversCart($cfOrder, $cart)
            ) {
                session()->flash('warning', trans('cashfree::app.response.payment-failed'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $data = (new OrderResource($cart))->jsonSerialize();

            $payments = $this->cashfree->getOrderPayments($orderId);
            $cfPaymentId = $payments[0]['cf_payment_id'] ?? $cfOrder['cf_order_id'] ?? $orderId;

            $data['payment']['additional'] = [
                'cashfree_order_id' => $orderId,
                'cf_order_id'       => $cfOrder['cf_order_id'] ?? '',
                'cf_payment_id'     => (string) $cfPaymentId,
                'cashfree_status'   => $orderStatus,
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                $this->orderTransactionRepository->create([
                    'transaction_id' => (string) $cfPaymentId,
                    'status'         => self::PAYMENT_SUCCESS,
                    'type'           => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id'       => $order->id,
                    'invoice_id'     => $invoice->id,
                    'amount'         => $order->base_grand_total,
                    'data'           => json_encode($cfOrder),
                ]);
            }

            Cart::deActivateCart();

            session()->flash('order_id', $order->id);
            session()->flash('success', trans('cashfree::app.response.payment-success'));

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Exception $e) {
            report($e);

            session()->flash('error', trans('cashfree::app.response.order-creation-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle payment cancellation.
     *
     * @return  RedirectResponse
     */
    public function cancel()
    {
        session()->flash('warning', trans('cashfree::app.response.payment-cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle incoming webhook notifications from Cashfree.
     *
     * @return  JsonResponse
     */
    public function webhook()
    {
        $rawPayload = request()->getContent();
        $timestamp  = request()->header('x-webhook-timestamp', '');
        $signature  = request()->header('x-webhook-signature', '');

        if ($signature && ! $this->cashfree->verifyWebhookSignature($rawPayload, $timestamp, $signature)) {
            return response()->json(['status' => 'invalid_signature'], 400);
        }

        $payload = request()->all();

        try {
            $orderId = $payload['data']['order']['order_id'] ?? null;
            $type    = $payload['type'] ?? '';

            if (! $orderId || ! in_array($type, ['PAYMENT_SUCCESS_WEBHOOK', 'ORDER_PAID_WEBHOOK'], true)) {
                return response()->json(['status' => 'skipped'], 200);
            }

            $cfOrder = $this->cashfree->getOrder($orderId);

            if (strtoupper($cfOrder['order_status'] ?? '') !== 'PAID') {
                return response()->json(['status' => 'payment_not_confirmed'], 200);
            }

            $cartId = null;

            if (preg_match('/^CF_(\d+)_/', $orderId, $matches)) {
                $cartId = (int) $matches[1];
            }

            if (! $cartId) {
                return response()->json(['status' => 'cart_not_found'], 200);
            }

            $cart = $this->cartRepository->find($cartId);

            if (! $cart || ! $cart->is_active) {
                return response()->json(['status' => 'cart_inactive'], 200);
            }

            $existingOrder = $this->orderRepository->findOneWhere(['cart_id' => $cartId]);

            if ($existingOrder) {
                return response()->json(['status' => 'order_already_exists', 'order_id' => $existingOrder->id], 200);
            }

            Cart::setCart($cart);
            Cart::collectTotals();
            $cart = Cart::getCart();

            if (! $cart) {
                return response()->json(['status' => 'cart_inactive'], 200);
            }

            $data = (new OrderResource($cart))->jsonSerialize();

            $payments = $this->cashfree->getOrderPayments($orderId);
            $cfPaymentId = $payments[0]['cf_payment_id'] ?? $cfOrder['cf_order_id'] ?? $orderId;

            $data['payment']['additional'] = [
                'cashfree_order_id' => $orderId,
                'cf_order_id'       => $cfOrder['cf_order_id'] ?? '',
                'cf_payment_id'     => (string) $cfPaymentId,
                'cashfree_status'   => 'PAID',
                'cashfree_source'   => 'webhook',
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                $this->orderTransactionRepository->create([
                    'transaction_id' => (string) $cfPaymentId,
                    'status'         => self::PAYMENT_SUCCESS,
                    'type'           => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id'       => $order->id,
                    'invoice_id'     => $invoice->id,
                    'amount'         => $order->base_grand_total,
                    'data'           => json_encode($cfOrder),
                ]);
            }

            Cart::deActivateCart();

            return response()->json(['status' => 'success', 'order_id' => $order->id], 200);
        } catch (\Exception $e) {
            report($e);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Check if paid amount covers cart total.
     *
     * @param  array   $cfOrder
     * @param  object  $cart
     */
    protected function paidAmountCoversCart(array $cfOrder, $cart): bool
    {
        $paid = $cfOrder['order_amount'] ?? null;

        if ($paid === null) {
            return true;
        }

        return (int) round((float) $paid * 100) === (int) round((float) $cart->base_grand_total * 100);
    }

    /**
     * Prepare invoice data.
     *
     * @param  object  $order
     * @return  array
     */
    protected function prepareInvoiceData($order): array
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
