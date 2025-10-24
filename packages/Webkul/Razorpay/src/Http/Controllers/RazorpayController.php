<?php

namespace Webkul\Razorpay\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Http\Controllers\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Razorpay\Models\RazorpayEvents;
use Webkul\Sales\Models\OrderPayment as OrderPayment;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;

class RazorpayController extends Controller
{
    /**
     * @return void
     */
    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected OrderRepository $orderRepository,
    ) {
    }

    /**
     * Redirects to checkout.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        $credentials = $this->getRazorpayCredentials();
        
        if (! $credentials['merchant_id'] || ! $credentials['private_key']) {
            return redirect()->back();
        }

        try {
            $api = new Api($credentials['merchant_id'], $credentials['private_key']);
            $cart = Cart::getCart();

            $orderAPI = $api->order->create([
                'amount'          => (int) $cart->base_grand_total * 100,
                'currency'        => 'INR',
                'receipt'         => 'receipt_' . $cart->id,
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

            $order = $this->orderRepository->findOneWhere(['cart_id' => $cart->id]);

            $pgUpdated = OrderPayment::where('order_id', $order->id)->firstOrFail();
            $pgUpdated->update([
                'additional' => [
                    'status'      => 'Pending Payment',
                    'oid'         => $orderAPI['id'],
                    'pgreference' => '',
                ],
            ]);

            $this->orderRepository->update(['status' => 'pending_payment'], $order->id);

            RazorpayEvents::create([
                'core_order_id'           => $order->id,
                'razorpay_order_id'       => $orderAPI['id'],
                'razorpay_invoice_status' => 'pending_payment',
            ]);

            return view('razorpay::drop-in-ui', compact('payment'));
        } catch (\Throwable $e) {
            \Log::error('Razorpay Redirect Error: ' . $e->getMessage());
            return redirect()->back()->withErrors('Payment initialization failed.');
        }
    }

    /**
     * Perform the transaction
     *
     * @return response
     */
    public function verifyPaymentHook(Request $request)
    {
        $credentials = $this->getRazorpayCredentials();

        if (! $credentials['private_key']) {
            return response()->json(['error' => 'Invalid credentials'], 400);
        }

        $eventId = $request->header('x-razorpay-event-id');
        $webhookSignature = $request->header('x-razorpay-signature');
        $webhookBody = json_decode($request->getContent());
        $generatedSignature = hash_hmac('sha256', $request->getContent(), $credentials['private_key']);

        try {
            if ($generatedSignature !== $webhookSignature) {
                return response()->json(['error' => 'Signature mismatch'], 403);
            }

            if (RazorpayEvents::where('razorpay_event_id', $eventId)->exists()) {
                return response()->json(['message' => 'Event already processed'], 200);
            }

            $paymentEntity = $webhookBody->payload->payment->entity;
            $paymentId = $paymentEntity->id;
            $orderId = $paymentEntity->order_id;
            $invoiceId = $paymentEntity->invoice_id;
            $pgStatus = $paymentEntity->status;

            $pgData = $invoiceId
                ? RazorpayEvents::where('razorpay_invoice_id', $invoiceId)->first()
                : RazorpayEvents::where('razorpay_order_id', $orderId)->first();

            if (! $pgData || ! in_array($pgStatus, ['captured', 'paid'])) {
                return response()->json(['message' => 'No matching order or invalid status'], 200);
            }

            $pgData->update([
                'razorpay_event_id'     => $eventId,
                'razorpay_invoice_id'   => $invoiceId,
                'razorpay_payment_id'   => $paymentId,
                'razorpay_invoice_status' => 'paid',
                'razorpay_signature'    => $webhookSignature,
            ]);

            $pgOrder = OrderPayment::where('order_id', $pgData->core_order_id)->firstOrFail();

            if (strtolower($pgOrder->additional['status'] ?? '') !== 'paid') {
                $pgOrder->update([
                    'additional' => [
                        'status'      => 'Paid',
                        'oid'         => $orderId,
                        'pgreference' => $paymentId,
                    ],
                ]);

                $this->orderRepository->update(['status' => 'processing'], $pgData->core_order_id);
                $this->invoiceRepository->create($this->prepareInvoiceData($pgData->core_order_id));
            }

            return response()->json(['message' => 'Payment verified'], 200);
        } catch (\Throwable $e) {
            \Log::error('Razorpay Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * payment cancelled
     */
    public function paymentFail(Request $request)
    {
        session()->flash('error', trans('razorpay::app.response.razorpay-cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Payment Success
     */
    public function paymentSuccess(Request $request)
    {
        $credentials = $this->getRazorpayCredentials();

        if (! $credentials['merchant_id'] || ! $credentials['private_key']) {
            session()->flash('error', 'Payment configuration is invalid.');
            return redirect()->route('shop.checkout.cart.index');
        }

        $order = $this->orderRepository->orderBy('created_at', 'desc')->first();

        if (! $order) {
            session()->flash('error', 'Order not found.');
            return redirect()->route('shop.checkout.cart.index');
        }

        if ($request->has('error')) {
            $errorDescription = $request['error']['description'] ?? 'Unknown error';

            OrderPayment::where('order_id', $order->id)->firstOrFail()->update([
                'additional' => ['status' => $errorDescription],
            ]);

            RazorpayEvents::where('razorpay_order_id', $order->id)->first()?->update([
                'razorpay_invoice_status' => 'error',
            ]);

            $this->orderRepository->update(['status' => 'pending_payment'], $order->id);

            Cart::deActivateCart();

            session()->flash('error', $errorDescription);
            session()->flash('order_id', $order->id);

            return redirect()->route('shop.checkout.onepage.success');
        }

        $api = new Api($credentials['merchant_id'], $credentials['private_key']);

        $expectedSignature = $request['razorpay_order_id'] . '|' . $request['razorpay_payment_id'];
        $generatedSignature = hash_hmac('sha256', $expectedSignature, $credentials['private_key']);

        if ($generatedSignature !== $request['razorpay_signature']) {
            session()->flash('error', trans("Something is not right, for security reasons the transaction can't be processed."));
            return redirect()->route('shop.checkout.cart.index');
        }

        OrderPayment::where('order_id', $order->id)->firstOrFail()->update([
            'additional' => [
                'status'      => 'Paid',
                'orderId'     => $request['razorpay_order_id'],
                'pgReference' => $request['razorpay_payment_id'],
            ],
        ]);

        $this->orderRepository->update(['status' => 'processing'], $order->id);
        $this->invoiceRepository->create($this->prepareInvoiceData($order->id));

        $payment = $api->order->fetch($request['razorpay_order_id'])->payments()->items[0] ?? null;

        if ($payment) {
            RazorpayEvents::where('razorpay_order_id', $request['razorpay_order_id'])->first()?->update([
                'razorpay_payment_id'     => $payment->id,
                'razorpay_invoice_status' => $payment->status,
                'razorpay_signature'      => $request['razorpay_signature'],
            ]);
        }

        Cart::deActivateCart();

        session()->flash('order_id', $order->id);

        return redirect()->route('shop.checkout.onepage.success');
    }

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    protected function prepareInvoiceData($oid = null): array
    {
        try {
            $order = $oid
                ? $this->orderRepository->findOrFail($oid)
                : $this->orderRepository->orderBy('created_at', 'desc')->first();

            if (! $order) {
                \Log::warning('No order found for invoice generation.');
                return [];
            }

            $invoiceItems = [];

            foreach ($order->items as $item) {
                $invoiceItems[$item->id] = $item->qty_to_invoice;
            }

            return [
                'order_id' => $order->id,
                'invoice'  => [
                    'items' => $invoiceItems,
                ],
            ];
        } catch (\Throwable $e) {
            \Log::error('Invoice preparation error: ' . $e->getMessage());
            return [];
        }
    }

    private function getRazorpayCredentials(): array
    {
        $isSandbox = core()->getConfigData('sales.payment_methods.razorpay.sandbox');

        return [
            'merchant_id' => core()->getConfigData(
                $isSandbox
                    ? 'sales.payment_methods.razorpay.test_client_id'
                    : 'sales.payment_methods.razorpay.client_id'
            ),
            'private_key' => core()->getConfigData(
                $isSandbox
                    ? 'sales.payment_methods.razorpay.test_client_secret'
                    : 'sales.payment_methods.razorpay.client_secret'
            ),
        ];
    }
}