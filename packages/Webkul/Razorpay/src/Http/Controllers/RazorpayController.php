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
        $merchantId = core()->getConfigData('sales.payment_methods.razorpay.client_id');

        $privateKey = core()->getConfigData('sales.payment_methods.razorpay.client_secret');

        if (core()->getConfigData('sales.payment_methods.razorpay.sandbox')) {
            $merchantId = core()->getConfigData('sales.payment_methods.razorpay.test_client_id');

            $privateKey = core()->getConfigData('sales.payment_methods.razorpay.test_client_secret');
        }

        if (
            $merchantId 
            && $privateKey
        ) {
            try {
                $api = new Api($merchantId, $privateKey);

                $cart = Cart::getCart();

                $cartAmount = $cart->base_grand_total;
                
                $cartId = $cart->id;

                $orderAPI  = $api->order->create([
                    'amount'          => (int) $cartAmount * 100, 
                    'currency'        => 'INR', 
                    'receipt'         => 'receipt_'. $cartId, 
                    'payment_capture' =>  1,
                ]);

                $pgOrderId = $orderAPI['id'];

                $payment = [
                    'key'           => $merchantId,
                    'amount'        => $cart->base_grand_total * 100,
                    'name'          => core()->getConfigData('sales.payment_methods.razorpay.merchant_name'),
                    'description'   => core()->getConfigData('sales.payment_methods.razorpay.merchant_desc'),
                    'image'         => bagisto_asset('images/logo.svg', 'admin'),
                    'prefill'       => [
                        'name'    => $cart->billing_address->name,
                        'email'   => $cart->billing_address->email,
                        'contact' => $cart->billing_address->phone,
                    ],
                    'notes'         => [
                        'shipping_address' => $cart->billing_address->address1,
                    ],
                    'order_id'      => $orderAPI->id,
                ];

                $data = (new OrderResource($cart))->jsonSerialize();

                $order = $this->orderRepository->create($data);

                $order = $this->orderRepository->findOneWhere([
                    'cart_id' => Cart::getCart()->id,
                ]);

                $pgUpdated = OrderPayment::where('order_id',$order->id)->firstOrFail();

                $additional = [];

                $additional['status'] = 'Pending Payment';

                $additional['oid'] = $pgOrderId;

                $additional['pgreference'] = '';

                $pgUpdated->additional = $additional;

                $pgUpdated->save();

                $this->orderRepository->update(['status' => 'pending_payment'], $order->id);

                $paymentEvents = new RazorpayEvents();

                $paymentEvents->core_order_id = $order->id;
 
                $paymentEvents->razorpay_order_id = $pgOrderId;

                $paymentEvents->razorpay_invoice_status = 'pending_payment';

                $paymentEvents->save();

                return view('razorpay::drop-in-ui', compact('payment'));
            } catch(\Exception $e){
                \Log::error('Error: ' . $e->getMessage());
            }
        } else {
           return redirect()->back();
        }
    }

    /**
     * Perform the transaction
     *
     * @return response
     */
    public function verifyPaymentHook(Request $request)
    {
        $merchantId = core()->getConfigData('sales.payment_methods.razorpay.client_id');

        $privateKey = core()->getConfigData('sales.payment_methods.razorpay.client_secret');

        if (core()->getConfigData('sales.payment_methods.razorpay.sandbox')) {
            $merchantId = core()->getConfigData('sales.payment_methods.razorpay.test_client_id');

            $privateKey = core()->getConfigData('sales.payment_methods.razorpay.test_client_secret');
        }

        $eventId = $request->header('x-razorpay-event-id');

        $webhookSignature = $request->header('x-razorpay-signature');

        $webhookBody = json_decode($request->getContent());

        $generatedSignature = hash_hmac('sha256',$request->getContent(), $privateKey);

        try {
            if ($generatedSignature == $webhookSignature){
                $payments= RazorpayEvents::where('razorpay_event_id',$eventId)->get();

                if($payments->count() == 0) {
                    $paymentId = $webhookBody->payload->payment->entity->id;

                    $order_id =  $webhookBody->payload->payment->entity->order_id;

                    $invoice_id = $webhookBody->payload->payment->entity->invoice_id;

                    $pgStatus = $webhookBody->payload->payment->entity->status;
            
                    $pgData = false;

                    if ($invoice_id) {
                        $pgData = RazorpayEvents::where('razorpay_invoice_id',$invoice_id)->first();
                    } elseif ($order_id) {
                        $pgData = RazorpayEvents::where('razorpay_order_id',$order_id)->first();
                    }
            
                    if($pgData) {
                        $pgId = $pgData->id;
            
                        if(
                            $pgStatus == 'captured' 
                            || $pgStatus == 'paid'
                        ) {
                            $rzp = RazorpayEvents::find($pgId);

                            $rzp->razorpay_event_id = $eventId;

                            $rzp->razorpay_invoice_id = $invoice_id;
                            
                            $rzp->razorpay_payment_id = $paymentId;

                            $rzp->razorpay_invoice_status = 'paid';

                            $rzp->razorpay_signature = $webhookSignature;

                            $rzp->save();
            
                            $pgOrder = OrderPayment::where('order_id',$pgData->core_order_id)->firstOrFail(); //->whereJsonContains('additional->status','Paid')
            
                            if(
                                $pgOrder->count() > 0  
                                && strtolower($pgOrder->additional['status']) != 'paid'
                            ) {
                                $pgUpdated = OrderPayment::where('order_id',$pgData->core_order_id)->firstOrFail();

                                $additional = [];

                                $additional['status'] = 'Paid';

                                $additional['oid'] = $order_id;

                                $additional['pgreference'] = $paymentId;

                                $pgUpdated->additional = $additional;

                                $pgUpdated->save();
            
                                $this->orderRepository->update(['status' => 'processing'], $pgData->core_order_id);

                                $this->invoiceRepository->create($this->prepareInvoiceData($pgData->core_order_id));
                            }
                        }
                    }
            
                }
            }
        } catch(\Exception $e){
            \Log::error('Error: ' . $e->getMessage());
        }

        return true;
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
        $merchantId = core()->getConfigData('sales.payment_methods.razorpay.client_id');

        $privateKey = core()->getConfigData('sales.payment_methods.razorpay.client_secret');

        if (core()->getConfigData('sales.payment_methods.razorpay.sandbox')) {
            $merchantId = core()->getConfigData('sales.payment_methods.razorpay.test_client_id');

            $privateKey = core()->getConfigData('sales.payment_methods.razorpay.test_client_secret');
        }

        if (isset($request['error'])) {
            $additional = [];
            
            $order = $this->orderRepository->orderBy('created_at', 'desc')->first();
            
            $pgUpdated = OrderPayment::where('order_id', $order->id)->firstOrFail();

            $additional['status'] = $request['error']['description'];
            $pgUpdated->additional = $additional;
            $pgUpdated->save();

            $rzp = RazorpayEvents::where('razorpay_order_id', $order->id)->first();
            $rzp->razorpay_invoice_status = 'error';
            $rzp->save();

            $this->orderRepository->update(['status' => 'pending_payment'], $order->id);

            session()->flash('error', $request['error']['description']);

            Cart::deActivateCart();

            session()->flash('order_id', $order->id);

            return redirect()->route('shop.checkout.onepage.success');
        } else {
            $oid = $request['razorpay_order_id'];

            $api = new Api($merchantId, $privateKey);

            $expectedSignature = $request['razorpay_order_id'] . '|' . $request['razorpay_payment_id'];

            $generatedSignature = hash_hmac('sha256', $expectedSignature, $privateKey);

            if ($generatedSignature !== $request['razorpay_signature']) {
                session()->flash('error', trans('Something is not right, for security reasons the transaction can\'t be processed.'));

                return redirect()->route('shop.checkout.cart.index');
            }

            $order = $this->orderRepository->orderBy('created_at', 'desc')->first();

            $paymentDetails = OrderPayment::where('order_id', $order->id)->firstOrFail();

            $additionalData = [
                'status'      => 'Paid',
                'orderId'     => $request['razorpay_order_id'],
                'pgReference' => $request['razorpay_payment_id'],
            ];

            $paymentDetails->additional = $additionalData;
            $paymentDetails->save();

            $this->orderRepository->update(['status' => 'processing'], $order->id);

            $this->invoiceRepository->create($this->prepareInvoiceData($order->id));

            $orderData = $api->order->fetch($oid)->payments();
            $payment = $orderData->items[0];

            $razorpayEvent = RazorpayEvents::where('razorpay_order_id', $request['razorpay_order_id'])->first();
            $razorpayEvent->razorpay_payment_id = $payment->id;
            $razorpayEvent->razorpay_invoice_status = $payment->status;
            $razorpayEvent->razorpay_signature = $request['razorpay_signature'];
            $razorpayEvent->update();

            Cart::deActivateCart();

            session()->flash('order_id', $order->id);

            return redirect()->route('shop.checkout.onepage.success');
        }
    }

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    protected function prepareInvoiceData($oid = null)
    {
        try {
            if ($oid) {
                $invoiceData = [
                    'order_id' => $oid,
                ];

                $order = $this->orderRepository->findOrFail($oid);

                foreach ($order->items as $item) {
                    $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
                }
            } else {
                $order = $this->orderRepository->orderBy('created_at', 'desc')->first();

                $invoiceData = [
                    'order_id' => $order->id,
                ];

                foreach ($order->items as $item) {
                    $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
                }
            }
        } catch(\Exception $e){
            \Log::error('Error: ' . $e->getMessage());
        }

        return $invoiceData;
    }
}