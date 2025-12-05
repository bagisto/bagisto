<?php

namespace Webkul\Razorpay\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Razorpay\Payment\RazorpayPayment;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;
use Webkul\Sales\Transformers\OrderResource;

class RazorpayController extends Controller
{
    /**
     * Payment captured constant.
     */
    public const PAYMENT_CAPTURED = 'captured';

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected RazorpayPayment $razorpayPayment,
        protected OrderRepository $orderRepository,
        protected OrderTransactionRepository $orderTransactionRepository,
        protected InvoiceRepository $invoiceRepository,
    ) {}

    /**
     * Redirects to checkout.
     */
    public function redirect(): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
    {
        if (! $this->razorpayPayment->hasValidCredentials()) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->back();
        }

        try {
            $cart = Cart::getCart();

            $currency = strtoupper($cart->base_currency_code ?? core()->getBaseCurrencyCode());

            if (! $this->razorpayPayment->isCurrencySupported($currency)) {
                session()->flash('error', trans('razorpay::app.response.supported-currency-error', [
                    'currency'            => $currency,
                    'supportedCurrencies' => implode(', ', $this->razorpayPayment->getSupportedCurrencies()),
                ]));

                return redirect()->back();
            }

            $razorpayOrder = $this->razorpayPayment->createOrder($cart);

            $payment = $this->razorpayPayment->preparePaymentData($cart, $razorpayOrder);

            return view('razorpay::drop-in-ui', compact('payment'));
        } catch (\Throwable $e) {
            report($e);

            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->back();
        }
    }

    /**
     * Payment success.
     */
    public function paymentSuccess(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (! $this->razorpayPayment->hasValidCredentials()) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $razorpayOrderId = $request->input('razorpay_order_id');

        if (! $razorpayOrderId) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if ($request->has('error')) {
            return $this->handlePaymentError($request);
        }

        $isValidSignature = $this->razorpayPayment->verifySignature(
            $request->input('razorpay_order_id'),
            $request->input('razorpay_payment_id'),
            $request->input('razorpay_signature')
        );

        if (! $isValidSignature) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->route('shop.checkout.cart.index');
        }

        return $this->handlePaymentSuccess($request, $cart);
    }

    /**
     * Payment fail.
     */
    public function paymentFail(): \Illuminate\Http\RedirectResponse
    {
        session()->flash('error', trans('razorpay::app.response.payment.cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle payment error.
     */
    protected function handlePaymentError(Request $request): \Illuminate\Http\RedirectResponse
    {
        $errorDescription = $request->input('error.description', trans('razorpay::app.response.something-went-wrong'));

        session()->flash('error', $errorDescription);

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle successful payment.
     */
    protected function handlePaymentSuccess(Request $request, $cart): \Illuminate\Http\RedirectResponse
    {
        try {
            $orderData = (new OrderResource($cart))->jsonSerialize();

            $order = $this->orderRepository->create($orderData);

            if ($order->payment) {
                $order->payment->update([
                    'additional' => [
                        'status'              => Invoice::STATUS_PAID,
                        'razorpay_order_id'   => $request->input('razorpay_order_id'),
                        'razorpay_payment_id' => $request->input('razorpay_payment_id'),
                    ],
                ]);
            }

            $this->orderRepository->update(['status' => Order::STATUS_PROCESSING], $order->id);

            $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order->id));

            $this->orderTransactionRepository->create([
                'transaction_id' => $request->input('razorpay_payment_id'),
                'status'         => self::PAYMENT_CAPTURED,
                'type'           => $order->payment->method,
                'payment_method' => $order->payment->method,
                'order_id'       => $order->id,
                'invoice_id'     => $invoice->id,
                'amount'         => $orderData['base_grand_total'] ?? 0,
                'data'           => json_encode([
                    'razorpay_order_id'   => $request->input('razorpay_order_id'),
                    'razorpay_payment_id' => $request->input('razorpay_payment_id'),
                    'razorpay_signature'  => $request->input('razorpay_signature'),
                ]),
            ]);

            Cart::deActivateCart();

            session()->flash('order_id', $order->id);

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Throwable $e) {
            report($e);

            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Prepares invoice data.
     */
    protected function prepareInvoiceData(?int $orderId = null): array
    {
        try {
            $order = $orderId
                ? $this->orderRepository->findOrFail($orderId)
                : $this->orderRepository->orderBy('created_at', 'desc')->first();

            if (! $order) {
                return [];
            }

            $invoiceItems = [];

            foreach ($order->items as $item) {
                if ($item->qty_to_invoice > 0) {
                    $invoiceItems[$item->id] = $item->qty_to_invoice;
                }
            }

            if (empty($invoiceItems)) {
                return [];
            }

            return [
                'order_id' => $order->id,
                'invoice'  => [
                    'items' => $invoiceItems,
                ],
            ];
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }
}
