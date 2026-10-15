<?php

namespace Webkul\Paypal\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Webkul\Checkout\Facades\Cart;
use Webkul\Paypal\Enums\IpnStatus;
use Webkul\Paypal\Helpers\Ipn;
use Webkul\Sales\Contracts\Order;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class StandardController extends Controller
{
    /**
     * Session key holding the cart a PayPal Standard payment was started for.
     */
    public const INTENT_KEY = 'paypal_standard_intent';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected Ipn $ipnHelper
    ) {}

    /**
     * Redirects to the paypal.
     *
     * @return View
     */
    public function redirect()
    {
        session()->put(self::INTENT_KEY, Cart::getCart()?->id);

        return view('paypal::standard-redirect');
    }

    /**
     * Cancel payment from paypal.
     *
     * @return Response
     */
    public function cancel()
    {
        session()->flash('error', trans('shop::app.checkout.cart.paypal-payment-cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Success payment.
     *
     * @return Response
     */
    public function success()
    {
        $cartId = session(self::INTENT_KEY);

        if (! $cartId) {
            return $this->paymentNotStarted();
        }

        if ($order = $this->orderRepository->findOneWhere(['cart_id' => $cartId])) {
            return $this->orderPlaced($order);
        }

        $cart = Cart::getCart();

        if ($cart?->id !== $cartId) {
            return $this->paymentNotStarted();
        }

        $order = $this->orderRepository->create((new OrderResource($cart))->jsonSerialize());

        Cart::deActivateCart();

        return $this->orderPlaced($order);
    }

    /**
     * Paypal IPN listener, answering with an error only when PayPal should send it again.
     */
    public function ipn(): JsonResponse
    {
        try {
            $status = $this->ipnHelper->processIpn(request()->all());
        } catch (\Throwable $e) {
            report($e);

            return new JsonResponse(['status' => IpnStatus::ERROR->value], 500);
        }

        return new JsonResponse(['status' => $status->value]);
    }

    /**
     * Send the customer to the order they came back to, whether it was just placed or already was.
     *
     * @param  Order  $order
     * @return Response
     */
    protected function orderPlaced($order)
    {
        session()->flash('order_id', $order->id);

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Turn away a return this session never started a payment for, so the endpoint cannot place an order on its own.
     *
     * @return Response
     */
    protected function paymentNotStarted()
    {
        session()->forget(self::INTENT_KEY);

        session()->flash('error', trans('paypal::app.errors.payment-not-confirmed'));

        return redirect()->route('shop.checkout.cart.index');
    }
}
