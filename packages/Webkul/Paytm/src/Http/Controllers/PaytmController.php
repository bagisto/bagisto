<?php

namespace Webkul\Paytm\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Paytm\Payment\Paytm;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class PaytmController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected Paytm $paytm
    ) {}

    /**
     * Redirects to Paytm payment page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function redirect()
    {
        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('shop::app.checkout.cart.mini-cart.empty-cart'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if (Cart::hasError()) {
            session()->flash('error', trans('paytm::app.checkout.onepage.payment.paytm.general-error'));

            return redirect()->route('shop.checkout.cart.index');
        }

        Cart::collectTotals();

        try {
            $this->validateOrder();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->route('shop.checkout.cart.index');
        }

        return view('paytm::checkout.redirect', [
            'paytmUrl' => $this->paytm->getPaytmUrl(),
            'paytmFields' => $this->paytm->getFormFields($cart),
        ]);
    }

    /**
     * Handle Paytm callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $payload = $request->all();
        $status = $payload['STATUS'] ?? '';
        $message = $payload['RESPMSG'] ?? null;

        if ($status === 'TXN_SUCCESS') {
            $orderId = $payload['ORDERID'] ?? $payload['ORDER_ID'] ?? null;

            $cartId = $this->extractCartId($orderId);

            return redirect()->route('paytm.success', [
                'cart_id' => $cartId,
                'payload' => $payload,
            ]);
        }

        return redirect()->route('paytm.cancel', [
            'message' => $message,
        ]);
    }

    /**
     * Handle Paytm cancel/failure redirect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request)
    {
        $message = $request->get('message');

        if (empty($message)) {
            $message = trans('paytm::app.checkout.onepage.payment.paytm.payment-failed');
        }

        session()->flash('error', $message);

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle Paytm success redirect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        $cartId = $request->get('cart_id', null);

        if (! $cartId) {
            return redirect()->route('paytm.cancel', [
                'message' => trans('paytm::app.checkout.onepage.payment.paytm.missing-cart-id'),
            ]);
        }

        $cart = $this->cartRepository->find($cartId);

        if (! $cart) {
            return redirect()->route('paytm.cancel', [
                'message' => trans('paytm::app.checkout.onepage.payment.paytm.cart-not-found'),
            ]);
        }

        $payload = $request->get('payload', []);

        if (! $this->paytm->verifyChecksum($payload)) {
            return redirect()->route('paytm.cancel', [
                'message' => trans('paytm::app.checkout.onepage.payment.paytm.checksum-failed'),
            ]);
        }

        Cart::setCart($cart);

        try {
            Cart::collectTotals();

            $this->validateOrder();

            $data = (new OrderResource($cart))->jsonSerialize();

            $order = $this->orderRepository->create($data);

            Cart::deActivateCart();

            if ($order->payment) {
                $order->payment->update([
                    'additional' => $this->formatPaymentAdditional($payload),
                ]);
            }

            session()->flash('order_id', $order->id);

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Exception $e) {
            $message = trans('paytm::app.checkout.onepage.payment.paytm.general-error');
        }

         return redirect()->route('paytm.cancel', [
            'message' => $message,
        ]);
    }

    /**
     * Validate order before creation.
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function validateOrder(): void
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = (float) core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: 0;

        if (! Cart::haveMinimumOrderAmount()) {
            throw new \Exception(trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->shipping_address
        ) {
            throw new \Exception(trans('shop::app.checkout.cart.check-shipping-address'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('shop::app.checkout.cart.check-billing-address'));
        }

        if (
            $cart->haveStockableItems()
            && ! $cart->selected_shipping_rate
        ) {
            throw new \Exception(trans('shop::app.checkout.cart.specify-shipping-method'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('shop::app.checkout.cart.specify-payment-method'));
        }
    }

    /**
     * Prepare payment additional data from Paytm response.
     *
     * @param  array  $payload
     * @return array
     */
    protected function formatPaymentAdditional(array $payload): array
    {
        $fields = [
            'ORDERID',
            'TXNID',
            'BANKTXNID',
            'STATUS',
            'RESPCODE',
            'RESPMSG',
            'TXNAMOUNT',
            'CURRENCY',
            'GATEWAYNAME',
            'BANKNAME',
            'PAYMODE',
        ];

        $additional = [];

        foreach ($fields as $field) {
            if (array_key_exists($field, $payload)) {
                $additional[$field] = $payload[$field];
            }
        }

        return $additional;
    }

    /**
     * Extract cart id from Paytm order id.
     *
     * @param  string|null  $orderId
     * @return int|null
     */
    protected function extractCartId(?string $orderId): ?int
    {
        if (! $orderId) {
            return null;
        }

        if (ctype_digit($orderId)) {
            return (int) $orderId;
        }

        $parts = explode('_', $orderId);
        $cartId = end($parts);

        return ctype_digit((string) $cartId) ? (int) $cartId : null;
    }
}
