<?php

namespace Webkul\Razorpay\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Webkul\Checkout\Facades\Cart;
use Webkul\Razorpay\Repositories\RazorpayEventRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class RazorpayController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected RazorpayEventRepository $razorpayEventRepository,
    ) {}

    /**
     * Redirects to checkout.
     */
    public function redirect(): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
    {
        $credentials = $this->getRazorpayCredentials();

        if (! $credentials['merchant_id'] || ! $credentials['private_key']) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->back();
        }

        try {
            $api = new Api($credentials['merchant_id'], $credentials['private_key']);

            $cart = Cart::getCart();

            $orderAPI = $api->order->create([
                'amount'          => (int) $cart->base_grand_total * 100,
                'currency'        => 'INR',
                'receipt'         => 'receipt_'.$cart->id,
                'payment_capture' => 1,
            ]);

            $payment = [
                'key'         => $credentials['merchant_id'],
                'amount'      => $cart->base_grand_total * 100,
                'name'        => core()->getConfigData('sales.payment_methods.razorpay.merchant_name'),
                'description' => core()->getConfigData('sales.payment_methods.razorpay.merchant_desc'),
                'image'       => bagisto_asset('images/logo.svg', 'admin'),

                'prefill'     => [
                    'name'    => $cart->billing_address->name,
                    'email'   => $cart->billing_address->email,
                    'contact' => $cart->billing_address->phone,
                ],

                'notes'       => [
                    'shipping_address' => $cart->billing_address->address1,
                ],

                'order_id'    => $orderAPI['id'],
            ];

            $orderData = (new OrderResource($cart))->jsonSerialize();

            $order = $this->orderRepository->create($orderData);

            if ($order->payment) {
                $order->payment->update([
                    'additional' => [
                        'status'      => 'Pending Payment',
                        'oid'         => $orderAPI['id'],
                        'pgreference' => '',
                    ],
                ]);
            }

            $this->orderRepository->update(['status' => 'pending_payment'], $order->id);

            $this->razorpayEventRepository->create([
                'order_id'                => $order->id,
                'razorpay_order_id'       => $orderAPI['id'],
                'razorpay_invoice_status' => 'pending_payment',
            ]);

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
        $credentials = $this->getRazorpayCredentials();

        if (! $credentials['merchant_id'] || ! $credentials['private_key']) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $razorpayOrderId = $request->input('razorpay_order_id') ?? $request->input('error.metadata.order_id');

        $order = null;

        if ($razorpayOrderId) {
            $razorpayEvent = $this->razorpayEventRepository->findOneWhere(['razorpay_order_id' => $razorpayOrderId]);

            if ($razorpayEvent) {
                $order = $this->orderRepository->find($razorpayEvent->order_id);
            }
        }

        if (! $order) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->route('shop.checkout.cart.index');
        }

        if ($request->has('error')) {
            return $this->handlePaymentError($request, $order);
        }

        if (! $this->verifySignature($request, $credentials['private_key'])) {
            session()->flash('error', trans('razorpay::app.response.something-went-wrong'));

            return redirect()->route('shop.checkout.cart.index');
        }

        return $this->handlePaymentSuccess($request, $order, $credentials);
    }

    /**
     * Payment fail.
     */
    public function paymentFail(Request $request): \Illuminate\Http\RedirectResponse
    {
        session()->flash('error', trans('razorpay::app.response.payment.cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle payment error.
     */
    protected function handlePaymentError(Request $request, $order): \Illuminate\Http\RedirectResponse
    {
        $errorDescription = $request->input('error.description', trans('razorpay::app.response.something-went-wrong'));

        if ($order->payment) {
            $order->payment->update([
                'additional' => ['status' => $errorDescription],
            ]);
        }

        $razorpayEvent = $this->razorpayEventRepository->findOneWhere(['order_id' => $order->id]);

        if ($razorpayEvent) {
            $this->razorpayEventRepository->update([
                'razorpay_invoice_status' => 'error',
            ], $razorpayEvent->id);
        }

        $this->orderRepository->update(['status' => 'pending_payment'], $order->id);

        Cart::deActivateCart();

        session()->flash('error', $errorDescription);

        session()->flash('order_id', $order->id);

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Handle successful payment.
     */
    protected function handlePaymentSuccess(Request $request, $order, array $credentials): \Illuminate\Http\RedirectResponse
    {
        if ($order->payment) {
            $order->payment->update([
                'additional' => [
                    'status'      => 'Paid',
                    'orderId'     => $request->input('razorpay_order_id'),
                    'pgReference' => $request->input('razorpay_payment_id'),
                ],
            ]);
        }

        $this->orderRepository->update(['status' => 'processing'], $order->id);

        $this->invoiceRepository->create($this->prepareInvoiceData($order->id));

        try {
            $api = new Api($credentials['merchant_id'], $credentials['private_key']);

            $payment = $api->order->fetch($request->input('razorpay_order_id'))->payments()->items[0] ?? null;

            if ($payment) {
                $razorpayEvent = $this->razorpayEventRepository->findOneWhere(['razorpay_order_id' => $request->input('razorpay_order_id')]);

                if ($razorpayEvent) {
                    $this->razorpayEventRepository->update([
                        'razorpay_payment_id'     => $payment->id,
                        'razorpay_invoice_status' => $payment->status,
                        'razorpay_signature'      => $request->input('razorpay_signature'),
                    ], $razorpayEvent->id);
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        Cart::deActivateCart();

        session()->flash('order_id', $order->id);

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Verify Razorpay signature.
     */
    protected function verifySignature(Request $request, string $privateKey): bool
    {
        $razorpayOrderId = $request->input('razorpay_order_id');

        $razorpayPaymentId = $request->input('razorpay_payment_id');

        $razorpaySignature = $request->input('razorpay_signature');

        if (! $razorpayOrderId || ! $razorpayPaymentId || ! $razorpaySignature) {
            return false;
        }

        $expectedSignature = $razorpayOrderId.'|'.$razorpayPaymentId;

        $generatedSignature = hash_hmac('sha256', $expectedSignature, $privateKey);

        return hash_equals($generatedSignature, $razorpaySignature);
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

    /**
     * Get Razorpay credentials.
     */
    protected function getRazorpayCredentials(): array
    {
        $isSandbox = core()->getConfigData('sales.payment_methods.razorpay.sandbox');

        $merchantId = core()->getConfigData(
            $isSandbox
                ? 'sales.payment_methods.razorpay.test_client_id'
                : 'sales.payment_methods.razorpay.client_id'
        );

        $privateKey = core()->getConfigData(
            $isSandbox
                ? 'sales.payment_methods.razorpay.test_client_secret'
                : 'sales.payment_methods.razorpay.client_secret'
        );

        return [
            'merchant_id' => $merchantId,
            'private_key' => $privateKey,
        ];
    }
}
