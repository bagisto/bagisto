<?php

namespace Webkul\Stripe\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Stripe\Charge;
use Stripe\Dispute;
use Webkul\Checkout\Contracts\Cart as CartContract;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Sales\Contracts\Order as OrderContract;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderCommentRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Repositories\RefundRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Stripe\Payment\Stripe;

class StripeController extends Controller
{
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
        protected RefundRepository $refundRepository,
        protected OrderCommentRepository $orderCommentRepository,
        protected Stripe $stripe,
    ) {}

    /**
     * Redirects to Stripe checkout.
     */
    public function redirect(): RedirectResponse
    {
        if (! $this->stripe->hasValidCredentials()) {
            session()->flash('error', trans('stripe::app.response.provide-credentials'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('stripe::app.response.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            $checkoutSession = $this->stripe->createCheckoutSession($cart);

            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            session()->flash('error', trans('stripe::app.response.payment-failed').': '.$e->getMessage());

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle the customer coming back from a paid checkout, showing the order the webhook may already have placed.
     */
    public function success(): RedirectResponse
    {
        $sessionId = request()->query('session_id');

        if (! $sessionId) {
            session()->flash('error', trans('stripe::app.response.invalid-session'));

            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            $session = $this->stripe->retrieveCheckoutSession($sessionId);

            if (! $session) {
                session()->flash('error', trans('stripe::app.response.session-invalid'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $cartId = $session->metadata->cart_id ?? null;

            if (! $cartId) {
                session()->flash('error', trans('stripe::app.response.cart-not-found'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $order = $this->placeOrder($session);

            if (! $order) {
                session()->flash('error', $this->cartRepository->find($cartId)?->is_active
                    ? trans('stripe::app.response.cart-changed')
                    : trans('stripe::app.response.cart-processed'));

                return redirect()->route('shop.checkout.cart.index');
            }

            session()->flash('order_id', $order->id);

            session()->flash('success', trans('stripe::app.response.payment-success'));

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Exception $e) {
            session()->flash('error', trans('stripe::app.response.verification-failed').': '.$e->getMessage());

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle payment cancellation.
     */
    public function cancel(): RedirectResponse
    {
        session()->flash('error', trans('stripe::app.response.payment-cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle an event Stripe sends about a payment, answering with an error only when Stripe should send it again.
     */
    public function webhook(): JsonResponse
    {
        $event = $this->stripe->constructWebhookEvent(request()->getContent(), request()->header('Stripe-Signature'));

        if (! $event) {
            return response()->json(['status' => 'invalid_signature'], 400);
        }

        try {
            $status = match ($event->type) {
                'checkout.session.completed', 'checkout.session.async_payment_succeeded' => $this->settleSession($event->data->object),
                'charge.refunded' => $this->recordRefund($event->data->object),
                'charge.dispute.created', 'charge.dispute.closed' => $this->recordDispute($event->type, $event->data->object),
                default => 'ignored',
            };
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['status' => 'error'], 500);
        }

        return response()->json(['status' => $status]);
    }

    /**
     * Place the order for a checkout Stripe reports as paid, whether or not the customer came back to the store.
     */
    protected function settleSession(object $session): string
    {
        if ($session->payment_status !== 'paid') {
            return 'payment_not_confirmed';
        }

        return $this->placeOrder($session) ? 'order_placed' : 'order_not_placed';
    }

    /**
     * Turn a paid checkout into an order once, however many times the customer and Stripe report it.
     */
    protected function placeOrder(object $session): ?OrderContract
    {
        $cartId = (int) ($session->metadata->cart_id ?? 0);

        if (! $cartId) {
            return null;
        }

        return Cache::lock('stripe.order.'.$cartId, 30)->block(10, function () use ($cartId, $session) {
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
                || ! $this->cartStillTotals($cart, $session)
            ) {
                return null;
            }

            $data = (new OrderResource($cart))->jsonSerialize();

            $data['payment']['additional'] = $paymentData = [
                'stripe_session_id' => $session->id,
                'stripe_payment_intent_id' => $session->payment_intent,
                'stripe_payment_status' => $session->payment_status,
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => Order::STATUS_PROCESSING], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                $this->orderTransactionRepository->create([
                    'transaction_id' => $session->payment_intent,
                    'status' => $session->payment_status,
                    'type' => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $order->base_grand_total,
                    'data' => json_encode($paymentData),
                ]);
            }

            Cart::deActivateCart();

            return $order;
        });
    }

    /**
     * Whether the cart still totals what it did when the payment started; a checkout from before that was recorded passes.
     */
    protected function cartStillTotals(CartContract $cart, object $session): bool
    {
        $total = $session->metadata->base_grand_total ?? null;

        if ($total === null) {
            return true;
        }

        return round((float) $total, 4) === round((float) $cart->base_grand_total, 4);
    }

    /**
     * Record the part of a charge's refunds the order does not have yet, refunding its items as well once all of it is refunded.
     */
    protected function recordRefund(Charge $charge): string
    {
        $order = $this->resolveOrder($charge->payment_intent);

        if (! $order) {
            return 'order_not_found';
        }

        return Cache::lock('stripe.refund.'.$order->id, 30)->block(10, function () use ($order, $charge) {
            $order = $this->orderRepository->find($order->id);

            $refunded = $this->stripe->fromStripeAmount((int) $charge->amount_refunded, $charge->currency);

            $remaining = round((float) $order->base_grand_total_invoiced - (float) $order->base_grand_total_refunded, 4);

            $amount = round(min($refunded - (float) $order->base_grand_total_refunded, $remaining), 4);

            if ($amount <= 0) {
                return 'refund_already_recorded';
            }

            $isFullRefund = $charge->refunded
                || $amount >= $remaining;

            $this->refundRepository->create([
                'order_id' => $order->id,
                'refund' => $isFullRefund ? $this->prepareFullRefundData($order, $amount) : [
                    'items' => [],
                    'shipping' => 0,
                    'adjustment_refund' => $amount,
                    'adjustment_fee' => 0,
                ],
            ]);

            $this->addComment($order, trans('stripe::app.webhook.refund-recorded', [
                'amount' => core()->formatBasePrice($amount),
                'total' => core()->formatBasePrice($refunded),
            ]));

            return 'refund_recorded';
        });
    }

    /**
     * Refund every item and the shipping still refundable, as a full refund in the admin does, settling the
     * difference from the amount Stripe refunded as an adjustment so the refund matches it exactly.
     */
    protected function prepareFullRefundData(OrderContract $order, float $amount): array
    {
        $data = [
            'items' => $order->items
                ->filter(fn ($item) => $item->qty_to_refund > 0)
                ->mapWithKeys(fn ($item) => [$item->id => $item->qty_to_refund])
                ->all(),
            'shipping' => max(0, (float) $order->base_shipping_invoiced - (float) $order->base_shipping_refunded),
            'adjustment_refund' => 0,
            'adjustment_fee' => 0,
        ];

        $total = $this->refundRepository->getOrderItemsRefundSummary($data, $order->id)['grand_total']['price'];

        return array_merge($data, [
            'adjustment_refund' => max(0, round($amount - $total, 4)),
            'adjustment_fee' => max(0, round($total - $amount, 4)),
        ]);
    }

    /**
     * Note on the order that the customer disputed the payment, or how the dispute ended.
     */
    protected function recordDispute(string $type, Dispute $dispute): string
    {
        $order = $this->resolveOrder($dispute->payment_intent);

        if (! $order) {
            return 'order_not_found';
        }

        $comment = $type === 'charge.dispute.created'
            ? trans('stripe::app.webhook.dispute-opened', [
                'amount' => core()->formatBasePrice($this->stripe->fromStripeAmount((int) $dispute->amount, $dispute->currency)),
                'reason' => str_replace('_', ' ', (string) $dispute->reason),
                'id' => $dispute->id,
            ])
            : trans('stripe::app.webhook.dispute-closed', [
                'status' => str_replace('_', ' ', (string) $dispute->status),
                'id' => $dispute->id,
            ]);

        return $this->addComment($order, $comment) ? 'dispute_recorded' : 'dispute_already_recorded';
    }

    /**
     * Find the order paid through a payment intent, placing it first when Stripe reports on a paid checkout
     * before the order exists, as it does for a dispute raised the moment a card is charged.
     */
    protected function resolveOrder(?string $paymentIntentId): ?OrderContract
    {
        if (empty($paymentIntentId)) {
            return null;
        }

        if ($order = $this->findOrderByPaymentIntent($paymentIntentId)) {
            return $order;
        }

        $session = $this->stripe->findCheckoutSession($paymentIntentId);

        if (
            ! $session
            || $session->payment_status !== 'paid'
        ) {
            return null;
        }

        return $this->placeOrder($session);
    }

    /**
     * Find the order paid through a Stripe payment intent.
     */
    protected function findOrderByPaymentIntent(string $paymentIntentId): ?OrderContract
    {
        $transaction = $this->orderTransactionRepository->findOneWhere([
            'transaction_id' => $paymentIntentId,
            'payment_method' => 'stripe',
        ]);

        return $transaction ? $this->orderRepository->find($transaction->order_id) : null;
    }

    /**
     * Add a note for the merchant to the order, unless the same note is already there from an earlier delivery.
     */
    protected function addComment(OrderContract $order, string $comment): bool
    {
        if ($this->orderCommentRepository->findOneWhere([
            'order_id' => $order->id,
            'comment' => $comment,
        ])) {
            return false;
        }

        $this->orderCommentRepository->create([
            'order_id' => $order->id,
            'comment' => $comment,
            'customer_notified' => 0,
        ]);

        return true;
    }

    /**
     * Prepare the invoice data for every item still to be invoiced.
     */
    protected function prepareInvoiceData(OrderContract $order): array
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
