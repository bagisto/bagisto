<?php

namespace Webkul\Paytm\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Paytm\Payment\Paytm;
use Webkul\Sales\Models\Order as OrderModel;
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
     */
    public function redirect()
    {
        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('shop::app.checkout.cart.mini-cart.empty-cart'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if (Cart::hasError()) {
            session()->flash('error', trans('paytm::app.shop.payment.general-error'));

            return redirect()->route('shop.checkout.cart.index');
        }

        Cart::collectTotals();

        try {
            $this->validateOrder();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect()->route('shop.checkout.cart.index');
        }

        return view('shop::handoff.paytm', [
            'paytmUrl' => $this->paytm->getPaytmUrl(),
            'paytmFields' => $this->paytm->getFormFields($cart),
        ]);
    }

    /**
     * Handle Paytm callback.
     */
    public function callback(Request $request)
    {
        $payload = $request->all();

        $status = $payload['STATUS'] ?? '';
        $message = $payload['RESPMSG'] ?? null;

        if ($status === 'TXN_SUCCESS') {
            $orderId = $payload['ORDERID'] ?? $payload['ORDER_ID'] ?? null;

            $cartId = $this->extractCartId($orderId);

            if (! $cartId) {
                return redirect()->route('paytm.cancel', [
                    'message' => trans('paytm::app.shop.payment.missing-cart-id'),
                ]);
            }

            $cart = $this->cartRepository->find($cartId);

            if (! $cart) {
                return redirect()->route('paytm.cancel', [
                    'message' => trans('paytm::app.shop.payment.cart-not-found'),
                ]);
            }

            if (! $this->paytm->verifyChecksum($payload)) {
                return redirect()->route('paytm.cancel', [
                    'message' => trans('paytm::app.shop.payment.checksum-failed'),
                ]);
            }

            Cart::setCart($cart);

            try {
                Cart::collectTotals();

                $this->validateOrder();

                $data = (new OrderResource($cart))->jsonSerialize();

                $order = $this->orderRepository->create($data);

                $this->orderRepository->update([
                    'status' => OrderModel::STATUS_PROCESSING,
                ], $order->id);

                if ($order->payment) {
                    $order->payment->update([
                        'additional' => $this->formatPaymentAdditional($payload),
                    ]);
                }

                Cart::deActivateCart();

                return redirect()->route('paytm.success', [
                    'orderId' => $order->id,
                    'message' => $message ?: trans('paytm::app.shop.payment.payment-success'),
                ]);
            } catch (\Exception $e) {
                $message = trans('paytm::app.shop.payment.general-error');
            }
        }

        return redirect()->route('paytm.cancel', [
            'message' => $message,
        ]);
    }

    /**
     * Handle Paytm cancel/failure redirect.
     */
    public function cancel(Request $request)
    {
        $customerMessage = $request->get('message') ?: trans('paytm::app.shop.payment.payment-failed');

        session()->flash('error', $customerMessage);

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle Paytm success redirect.
     */
    public function success(Request $request)
    {
        $customerMessage = $request->get('message') ?: trans('paytm::app.shop.payment.payment-success');
        $orderId = $request->get('orderId');

        session()->flash('success', $customerMessage);

        if ($orderId) {
            session()->flash('order_id', $orderId);
        }

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Validate order before creation.
     *
     * @return void|\Exception
     */
    protected function validateOrder()
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
     * Prepare payment additional data.
     *
     * @param  array  $payload
     * @return array
     */
    protected function formatPaymentAdditional(array $payload)
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
    protected function extractCartId(?string $orderId)
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
