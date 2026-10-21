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
     * Settle the payment as the customer comes back from PayGlocal's hosted checkout, here because
     * this is the last point still covered by PayGlocal's signature.
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
     * Show the customer what became of the payment settled in the callback, looking an order up by
     * the reference in the query string and never creating one.
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
     * Confirm a payment with PayGlocal and turn it into an order, reading the outcome back from the
     * gateway so a replayed or stale token settles nothing the gateway would not.
     */
    protected function settle(array $claims): ?OrderContract
    {
        $cartId = $this->payGlocal->parseCartId($claims['merchantTxnId'] ?? null);

        if (! $cartId) {
            return null;
        }

        $statusUrl = $claims['statusUrl'] ?? null;

        if (! $statusUrl) {
            return null;
        }

        $response = $this->payGlocal->getTransactionStatus($statusUrl);

        if (! $response) {
            throw new \Exception('PayGlocal did not answer the status call for cart '.$cartId.'.');
        }

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
     * Whether the cart still totals what PayGlocal reports having taken, measured against the
     * base total the payment was started for and refused when no amount is reported at all.
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
            return false;
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
