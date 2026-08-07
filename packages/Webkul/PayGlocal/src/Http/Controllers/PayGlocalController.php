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

        /**
         * The cart is carried inside the reference PayGlocal echoes back, so the payment can
         * be traced to it later without keeping a record of the attempt on this side.
         */
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
     * Receive the customer coming back from PayGlocal's hosted checkout.
     *
     * PayGlocal posts back a single `x-gl-token` field, which is a JWS it signed. The payment is
     * deliberately not settled here. This route runs without the session middleware, because a
     * browser does not send the session cookie on a cross site POST under the `lax` policy
     * Laravel defaults to: starting a session here would hand the browser a new empty one and
     * sign the customer out of the storefront and the admin alike. The reference is handed to a
     * redirect instead, which the browser follows as an ordinary navigation and which does carry
     * the original session. This is the shape core Stripe gets for free by being sent back with a
     * GET and its reference in the query string.
     */
    public function callback(): RedirectResponse
    {
        $token = (string) request()->input('x-gl-token');

        $claims = $token ? $this->crypto->verify($token) : null;

        return redirect()->route('payglocal.success', array_filter([
            'gid' => $claims['gid'] ?? null,
            'merchantTxnId' => $claims['merchantTxnId'] ?? null,
        ]));
    }

    /**
     * Settle the payment the customer has returned from.
     *
     * Reached by redirect from the callback, so the session is intact and the customer is still
     * signed in. Nothing in the query string is trusted: it says only which payment to ask about,
     * and the outcome is read back from PayGlocal before an order is placed, exactly as the
     * webhook does with the reference it is handed.
     */
    public function success(): RedirectResponse
    {
        try {
            $gid = request()->query('gid');

            if (! $gid) {
                session()->flash('error', trans('payglocal::app.response.verification-failed'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $cartId = $this->payGlocal->parseCartId(request()->query('merchantTxnId'));

            if (
                $cartId
                && $order = $this->findOrder($cartId)
            ) {
                session()->flash('order_id', $order->id);

                return redirect()->route('shop.checkout.onepage.success');
            }

            $response = $this->payGlocal->getTransactionStatus($gid);

            $cartId = $cartId ?: $this->resolveCartId($response);

            if (! $cartId) {
                session()->flash('error', trans('payglocal::app.response.transaction-not-found'));

                return redirect()->route('shop.checkout.cart.index');
            }

            if ($order = $this->findOrder($cartId)) {
                session()->flash('order_id', $order->id);

                return redirect()->route('shop.checkout.onepage.success');
            }

            $status = $this->resolveStatus($response);

            if (! $status?->isSuccessful()) {
                return $this->redirectUnsuccessful($status);
            }

            $order = $this->placeOrder($cartId, $gid, $response);

            if (! $order) {
                session()->flash('error', trans('payglocal::app.response.order-creation-failed'));

                return redirect()->route('shop.checkout.cart.index');
            }

            session()->flash('order_id', $order->id);

            session()->flash('success', trans('payglocal::app.response.payment-success'));

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Throwable $e) {
            report($e);

            session()->flash('error', trans('payglocal::app.response.order-creation-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Send the customer back to the cart, telling them what actually became of their payment.
     */
    protected function redirectUnsuccessful(?PayGlocalPaymentStatus $status): RedirectResponse
    {
        if (
            ! $status
            || $status->isPending()
        ) {
            session()->flash('warning', trans('payglocal::app.response.payment-pending'));

            return redirect()->route('shop.checkout.cart.index');
        }

        session()->flash('error', $status->isCancelled()
            ? trans('payglocal::app.response.payment-cancelled')
            : trans('payglocal::app.response.payment-failed'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle a server-to-server notification from PayGlocal.
     */
    public function webhook(): JsonResponse
    {
        try {
            $reference = $this->resolveWebhookReference();

            $gid = $reference['gid'] ?? null;

            $cartId = $this->payGlocal->parseCartId($reference['merchantTxnId'] ?? null);

            if (
                $cartId
                && $order = $this->findOrder($cartId)
            ) {
                return response()->json([
                    'status' => 'order_already_exists',
                    'order_id' => $order->id,
                ], 200);
            }

            $response = $this->payGlocal->getTransactionStatus($gid);

            $cartId = $cartId ?: $this->resolveCartId($response);

            if (! $cartId) {
                logger()->warning('PayGlocal webhook could not be matched to a payment.', [
                    'reference' => $reference,
                ]);

                return response()->json(['status' => 'transaction_not_found'], 200);
            }

            if ($order = $this->findOrder($cartId)) {
                return response()->json([
                    'status' => 'order_already_exists',
                    'order_id' => $order->id,
                ], 200);
            }

            $status = $this->resolveStatus($response);

            if (! $status?->isSuccessful()) {
                return response()->json(['status' => 'payment_not_confirmed'], 200);
            }

            $order = $this->placeOrder($cartId, $gid, $response);

            if (! $order) {
                return response()->json(['status' => 'order_creation_failed'], 200);
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
     * Read the reference identifying which payment a webhook concerns.
     *
     * Only a token PayGlocal signed is accepted, so an unauthenticated caller cannot drive
     * the settlement path with a reference of their own choosing.
     */
    protected function resolveWebhookReference(): array
    {
        $token = request()->input('x-gl-token') ?: trim(request()->getContent());

        if (! $token) {
            return [];
        }

        return $this->crypto->verify($token) ?? [];
    }

    /**
     * Work out which cart a payment belongs to from what PayGlocal reports about it.
     */
    protected function resolveCartId(?array $response): ?int
    {
        return $this->payGlocal->parseCartId(
            $this->payGlocal->getReportedMerchantTxnId($response)
        );
    }

    /**
     * Find the order already placed for a cart, if there is one.
     */
    protected function findOrder(int $cartId): ?OrderContract
    {
        return $this->orderRepository->findOneWhere(['cart_id' => $cartId]);
    }

    /**
     * Map PayGlocal's reported status onto a known outcome.
     */
    protected function resolveStatus(?array $response): ?PayGlocalPaymentStatus
    {
        return PayGlocalPaymentStatus::tryFrom(strtoupper($response['status'] ?? ''));
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

            Cart::collectTotals();

            if (! $this->amountMatches($cart, $response)) {
                logger()->error('PayGlocal amount mismatch. The cart changed while the payment was in progress.', [
                    'gid' => $gid,
                    'captured_amount' => $this->payGlocal->getCapturedAmount($response),
                    'captured_currency' => $this->payGlocal->getCapturedCurrency($response),
                    'cart_amount' => $cart->grand_total,
                    'cart_currency' => $cart->cart_currency_code,
                ]);

                return null;
            }

            $data = (new OrderResource($cart))->jsonSerialize();

            $data['payment']['additional'] = [
                'payglocal_gid' => $gid,
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
     * PayGlocal is the only side holding the captured figure now, so the check applies when
     * it reports one and is skipped when it does not, which leaves the flow no weaker than
     * the other gateways, none of which compare amounts at all.
     */
    protected function amountMatches($cart, ?array $response): bool
    {
        $currency = $this->payGlocal->getCapturedCurrency($response);

        if (
            $currency
            && strtoupper((string) $cart->cart_currency_code) !== $currency
        ) {
            return false;
        }

        $amount = $this->payGlocal->getCapturedAmount($response);

        if ($amount === null) {
            return true;
        }

        $decimal = $this->payGlocal->getCurrencyDecimal($currency ?? $cart->cart_currency_code);

        return round($amount, $decimal) === round((float) $cart->grand_total, $decimal);
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
