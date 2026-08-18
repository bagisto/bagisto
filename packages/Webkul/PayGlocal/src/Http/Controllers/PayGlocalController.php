<?php

namespace Webkul\PayGlocal\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\PayGlocal\Enums\PayGlocalPaymentStatus;
use Webkul\PayGlocal\Helpers\Crypto;
use Webkul\PayGlocal\Payment\PayGlocal;
use Webkul\Sales\Contracts\Order as OrderContract;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Controllers\Controller;

class PayGlocalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected PayGlocal $payGlocal,
        protected Crypto $crypto,
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected OrderTransactionRepository $orderTransactionRepository,
        protected InvoiceRepository $invoiceRepository,
    ) {}

    /**
     * Initiate the payment and send the customer to PayGlocal's hosted checkout.
     */
    public function redirect(): RedirectResponse
    {
        if (
            ! $this->payGlocal->hasValidCredentials()
            || ! $this->payGlocal->hasUsableKeys()
        ) {
            session()->flash('error', trans('payglocal::app.response.provide-credentials'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('payglocal::app.response.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $currency = $this->payGlocal->getCurrency($cart);

        if (! $this->payGlocal->isCurrencySupported($currency)) {
            session()->flash('error', trans('payglocal::app.response.supported-currency-error', [
                'currency' => $currency,
                'supportedCurrencies' => implode(', ', $this->payGlocal->getAcceptedCurrencies()),
            ]));

            return redirect()->route('shop.checkout.cart.index');
        }

        $response = $this->payGlocal->initiatePayment(
            $cart,
            $this->payGlocal->generateMerchantTxnId($cart->id)
        );

        if (! $response) {
            session()->flash('error', trans('payglocal::app.response.payment-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        return redirect()->away($response['redirectUrl']);
    }

    /**
     * Receive the customer coming back from PayGlocal's hosted checkout, and settle the payment.
     *
     * PayGlocal posts back a single `x-gl-token` field, a JWS it signed, carrying the reference,
     * the outcome and the status URL to confirm it against. The order is placed here rather than
     * after the redirect because this is the last point at which any of that is still covered by
     * PayGlocal's signature - past the redirect there is only a query string a customer can type.
     *
     * This route runs without the session middleware, because a browser does not send the session
     * cookie on a cross site POST under the `lax` policy Laravel defaults to: starting a session
     * here would hand the browser a new empty one and sign the customer out of the storefront and
     * the admin alike. Order creation does not need one, and the session writes the cart makes on
     * the way through land in a request-lifetime store that is never sent back.
     */
    public function callback(): RedirectResponse
    {
        $claims = $this->verifiedClaims();

        try {
            $this->settle($claims);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('payglocal.success', array_filter([
            'gid' => $claims['gid'] ?? null,
            'merchantTxnId' => $claims['merchantTxnId'] ?? null,
        ]));
    }

    /**
     * Show the customer what became of the payment settled in the callback.
     *
     * Nothing here is trusted: the query string only says which cart to look at, and the answer
     * is whether an order exists for it. A customer typing a reference of their own can therefore
     * never cause an order, only ever look for one.
     */
    public function success(): RedirectResponse
    {
        $cartId = $this->payGlocal->parseCartId(request()->query('merchantTxnId'))
            ?? Cart::getCart()?->id;

        $order = $cartId ? $this->findOrder($cartId) : null;

        if (! $order) {
            session()->flash('error', trans('payglocal::app.response.payment-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }

        session()->flash('order_id', $order->id);

        session()->flash('success', trans('payglocal::app.response.payment-success'));

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Handle a server-to-server notification from PayGlocal.
     */
    public function webhook(): JsonResponse
    {
        try {
            $claims = $this->verifiedClaims();

            if (! $claims) {
                return response()->json(['status' => 'transaction_not_found'], 200);
            }

            $cartId = $this->payGlocal->parseCartId($claims['merchantTxnId'] ?? null);

            if (
                $cartId
                && $order = $this->findOrder($cartId)
            ) {
                return response()->json([
                    'status' => 'order_already_exists',
                    'order_id' => $order->id,
                ], 200);
            }

            $order = $this->settle($claims);

            if (! $order) {
                return response()->json(['status' => 'payment_not_confirmed'], 200);
            }

            return response()->json([
                'status' => 'order_created',
                'order_id' => $order->id,
            ], 200);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Read the claims of the token PayGlocal signed, or nothing if it did not sign it.
     */
    protected function verifiedClaims(): array
    {
        $token = (string) (request()->input('x-gl-token') ?: trim(request()->getContent()));

        if (! $token) {
            return [];
        }

        return $this->crypto->verify($token) ?? [];
    }

    /**
     * Confirm a payment with PayGlocal and turn it into an order.
     *
     * The signed claims say which payment this is and where to confirm it; the confirmation
     * itself is read back from PayGlocal rather than taken from the token, so a replayed or
     * stale token cannot settle anything the gateway would not settle.
     */
    protected function settle(array $claims): ?OrderContract
    {
        $cartId = $this->payGlocal->parseCartId($claims['merchantTxnId'] ?? null);

        if (! $cartId) {
            return null;
        }

        $response = $this->payGlocal->getTransactionStatus($claims['statusUrl'] ?? null) ?? $claims;

        $status = PayGlocalPaymentStatus::tryFrom(strtoupper($response['status'] ?? ''));

        if (! $status?->isSuccessful()) {
            return null;
        }

        return $this->placeOrder($cartId, $claims['gid'] ?? null, $response);
    }

    /**
     * Find the order already placed for a cart, if there is one.
     */
    protected function findOrder(int $cartId): ?OrderContract
    {
        return $this->orderRepository->findOneWhere(['cart_id' => $cartId]);
    }

    /**
     * Turn a captured payment into an order.
     */
    protected function placeOrder(int $cartId, ?string $gid, ?array $response): ?OrderContract
    {
        return Cache::lock('payglocal.order.'.$cartId, 30)->block(10, function () use ($cartId, $gid, $response) {
            if ($order = $this->findOrder($cartId)) {
                return $order;
            }

            $cart = $this->cartRepository->find($cartId);

            if (! $cart || ! $cart->is_active) {
                return null;
            }

            Cart::setCart($cart);

            core()->setCurrentCurrency($cart->cart_currency_code);

            Cart::collectTotals();

            $cart = Cart::getCart();

            if (! $cart) {
                return null;
            }

            if (! $this->amountMatches($cart, $response)) {
                return null;
            }

            $data = (new OrderResource($cart))->jsonSerialize();

            $data['payment']['additional'] = [
                'payglocal_gid' => $gid,
                'payglocal_merchant_txn_id' => $this->payGlocal->getReportedMerchantTxnId($response),
                'payglocal_status' => PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value,
            ];

            $order = $this->orderRepository->create($data);

            $this->orderRepository->update(['status' => Order::STATUS_PROCESSING], $order->id);

            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));

                $this->orderTransactionRepository->create([
                    'transaction_id' => $gid,
                    'status' => PayGlocalPaymentStatus::SENT_FOR_CAPTURE->value,
                    'type' => $order->payment->method,
                    'payment_method' => $order->payment->method,
                    'order_id' => $order->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $order->base_grand_total,
                    'data' => json_encode($response),
                ]);
            }

            Cart::deActivateCart();

            return $order;
        });
    }

    /**
     * Check that the cart still totals what PayGlocal reports having taken.
     *
     * Measured against the base total, because that is the figure the payment was started for
     * and the one the transaction is recorded against.
     */
    protected function amountMatches($cart, ?array $response): bool
    {
        $currency = $this->payGlocal->getCapturedCurrency($response);

        if (
            $currency
            && $this->payGlocal->getCurrency($cart) !== $currency
        ) {
            return false;
        }

        $amount = $this->payGlocal->getCapturedAmount($response);

        if ($amount === null) {
            return true;
        }

        $decimal = $this->payGlocal->getCurrencyDecimal($this->payGlocal->getCurrency($cart));

        return round($amount, $decimal) === round((float) $cart->base_grand_total, $decimal);
    }

    /**
     * Prepare invoice data.
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
