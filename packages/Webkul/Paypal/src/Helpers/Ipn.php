<?php

namespace Webkul\Paypal\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Paypal\Enums\IpnStatus;
use Webkul\Paypal\Enums\PaypalPaymentStatus;
use Webkul\Paypal\Payment\Standard;
use Webkul\Sales\Contracts\Order as OrderContract;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;

class Ipn
{
    /**
     * The command that asks PayPal to confirm a notification is one it sent.
     */
    public const VALIDATION_COMMAND = '_notify-validate';

    /**
     * PayPal's answer when it confirms the notification is its own.
     */
    public const VERIFIED_RESPONSE = 'VERIFIED';

    /**
     * The transaction type of a subscription payment, which this helper settles nothing for.
     */
    public const RECURRING_TRANSACTION_TYPE = 'recurring_payment';

    /**
     * The payment type recorded against the transaction when PayPal names none.
     */
    public const DEFAULT_PAYMENT_TYPE = 'instant';

    /**
     * Seconds a cart's settlement is locked for, so the buyer's return and a retry cannot both place it.
     */
    public const LOCK_SECONDS = 30;

    /**
     * Seconds to wait for another request's settlement of the same cart to finish.
     */
    public const LOCK_WAIT_SECONDS = 10;

    /**
     * The places a currency is paid in when the store records none for it.
     */
    public const DEFAULT_CURRENCY_DECIMAL = 2;

    /**
     * IPN post data.
     */
    protected array $post;

    /**
     * The order the notification is about.
     */
    protected ?OrderContract $order = null;

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected Standard $paypalStandard,
        protected CartRepository $cartRepository,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected OrderTransactionRepository $orderTransactionRepository
    ) {}

    /**
     * Settle the payment PayPal reports, placing the order itself when the buyer never came back.
     */
    public function processIpn(array $post): IpnStatus
    {
        $this->post = $post;

        if (! $this->postBack()) {
            return IpnStatus::NOT_VERIFIED;
        }

        if (
            empty($this->post['txn_type'])
            || $this->post['txn_type'] === self::RECURRING_TRANSACTION_TYPE
        ) {
            return IpnStatus::IGNORED;
        }

        if (PaypalPaymentStatus::tryFrom($this->post['payment_status'] ?? '') !== PaypalPaymentStatus::COMPLETED) {
            return IpnStatus::IGNORED;
        }

        if (! $this->paidToThisStore()) {
            return IpnStatus::WRONG_RECEIVER;
        }

        return $this->settlePayment();
    }

    /**
     * Post back to PayPal to check whether this request is a valid one.
     *
     * @return bool
     */
    protected function postBack()
    {
        $url = $this->paypalStandard->getIPNUrl();

        try {
            $response = Http::asForm()
                ->post($url, ['cmd' => self::VALIDATION_COMMAND] + $this->post);

            return $response->successful() && $response->body() === self::VERIFIED_RESPONSE;
        } catch (\Exception $e) {
            report($e);

            return false;
        }
    }

    /**
     * Whether the money went to this store's PayPal account rather than to someone else's.
     */
    protected function paidToThisStore(): bool
    {
        $account = $this->paypalStandard->getConfigData('business_account');

        if (! $account) {
            return false;
        }

        $receivers = array_filter([
            $this->post['receiver_email'] ?? null,
            $this->post['business'] ?? null,
        ]);

        foreach ($receivers as $receiver) {
            if (strcasecmp($receiver, $account) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Turn the paid cart into a processing order once, however many times PayPal reports it.
     */
    protected function settlePayment(): IpnStatus
    {
        $cartId = (int) ($this->post['invoice'] ?? 0);

        if (! $cartId) {
            return IpnStatus::CART_NOT_IDENTIFIED;
        }

        $lock = Cache::lock('paypal.standard.order.'.$cartId, self::LOCK_SECONDS);

        return $lock->block(self::LOCK_WAIT_SECONDS, function () use ($cartId) {
            $this->order = $this->orderRepository->findOneWhere(['cart_id' => $cartId])
                ?: $this->placeOrder($cartId);

            if (! $this->order) {
                return IpnStatus::ORDER_NOT_PLACED;
            }

            if (! $this->paymentCovers($this->order->grand_total, $this->order->order_currency_code)) {
                return IpnStatus::AMOUNT_MISMATCH;
            }

            return $this->processOrder();
        });
    }

    /**
     * Place the order for a cart PayPal reports as paid, whether or not the buyer came back to the store.
     */
    protected function placeOrder(int $cartId): ?OrderContract
    {
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
            || ! $this->paymentCovers($cart->grand_total, $cart->cart_currency_code)
        ) {
            return null;
        }

        $order = $this->orderRepository->create((new OrderResource($cart))->jsonSerialize());

        Cart::deActivateCart();

        return $order;
    }

    /**
     * Whether PayPal reports the amount, in the currency, the order or cart was raised for.
     */
    protected function paymentCovers($grandTotal, ?string $currencyCode): bool
    {
        $decimal = $this->currencyDecimal($currencyCode);

        return round((float) ($this->post['mc_gross'] ?? 0), $decimal) === round((float) $grandTotal, $decimal)
            && strtoupper((string) ($this->post['mc_currency'] ?? '')) === strtoupper((string) $currencyCode);
    }

    /**
     * How many places a currency is paid in, so a total carried at four places is compared
     * against what PayPal actually charged rather than refused for the difference.
     */
    protected function currencyDecimal(?string $currencyCode): int
    {
        if (! $currencyCode) {
            return self::DEFAULT_CURRENCY_DECIMAL;
        }

        return core()->getAllCurrencies()->firstWhere('code', strtoupper($currencyCode))?->decimal
            ?? self::DEFAULT_CURRENCY_DECIMAL;
    }

    /**
     * Move the order to processing and record the payment against it, once.
     */
    protected function processOrder(): IpnStatus
    {
        $this->order = $this->orderRepository->update(['status' => Order::STATUS_PROCESSING], $this->order->id);

        if (! $this->order->canInvoice()) {
            return IpnStatus::ALREADY_SETTLED;
        }

        $invoice = $this->invoiceRepository->create($this->prepareInvoiceData());

        $this->orderTransactionRepository->create([
            'transaction_id' => $this->post['txn_id'],
            'status' => $this->post['payment_status'],
            'type' => $this->post['payment_type'] ?? self::DEFAULT_PAYMENT_TYPE,
            'payment_method' => $this->order->payment->method,
            'order_id' => $this->order->id,
            'invoice_id' => $invoice->id,
            'amount' => $this->order->grand_total,
            'data' => json_encode($this->post),
        ]);

        return IpnStatus::ORDER_SETTLED;
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = ['order_id' => $this->order->id];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
