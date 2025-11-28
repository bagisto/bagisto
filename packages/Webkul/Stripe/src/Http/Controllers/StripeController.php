<?php

namespace Webkul\Stripe\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Stripe\Enums\StripeTransactionStatus;
use Webkul\Stripe\Payment\Stripe;
use Webkul\Stripe\Repositories\StripeTransactionRepository;

class StripeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected Stripe $stripe,
        protected StripeTransactionRepository $stripeTransactionRepository,
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
    ) {}

    /**
     * Redirects to Stripe checkout.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect()
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

            $this->stripeTransactionRepository->create([
                'cart_id'     => $cart->id,
                'customer_id' => $cart->customer_id,
                'session_id'  => $checkoutSession->id,
                'amount'      => $cart->base_grand_total,
                'status'      => StripeTransactionStatus::PENDING->value,
            ]);

            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            session()->flash('error', trans('stripe::app.response.payment-failed').': '.$e->getMessage());

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle payment success callback.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success()
    {
        $sessionId = request()->get('session_id');

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

            $stripeTransaction = $this->stripeTransactionRepository->where('session_id', $sessionId)->first();

            if (! $stripeTransaction) {
                session()->flash('error', trans('stripe::app.response.session-not-found'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $cart = $this->cartRepository->find($stripeTransaction->cart_id);

            if (! $cart || ! $cart->is_active) {
                session()->flash('error', trans('stripe::app.response.cart-processed'));

                return redirect()->route('shop.checkout.cart.index');
            }

            Cart::setCart($cart);

            Cart::collectTotals();

            $data = (new OrderResource($cart))->jsonSerialize();

            $data['payment']['additional'] = [
                'stripe_session_id'        => $sessionId,
                'stripe_payment_intent_id' => $session->payment_intent,
                'stripe_payment_status'    => $session->payment_status,
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            if ($order->canInvoice()) {
                $invoiceData = [
                    'order_id' => $order->id,
                ];

                foreach ($order->items as $item) {
                    $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
                }

                $this->invoiceRepository->create($invoiceData);
            }

            Cart::deActivateCart();

            $this->stripeTransactionRepository->update([
                'status' => StripeTransactionStatus::COMPLETED->value,
            ], $stripeTransaction->id);

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
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        $sessionId = request()->get('session_id');

        if ($sessionId) {
            $stripeTransaction = $this->stripeTransactionRepository->where('session_id', $sessionId)->first();

            if ($stripeTransaction) {
                $this->stripeTransactionRepository->update([
                    'status' => StripeTransactionStatus::CANCELLED->value,
                ], $stripeTransaction->id);
            }
        }

        session()->flash('error', trans('stripe::app.response.payment-cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }
}
