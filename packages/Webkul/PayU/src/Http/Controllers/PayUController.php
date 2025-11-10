<?php

namespace Webkul\PayU\Http\Controllers;

use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Shop\Http\Controllers\Controller;

class PayUController extends Controller
{
    /**
     * Merchant credentials
     */
    protected $merchantKey;
    protected $merchantSalt;
    protected $isSandbox;
    protected $paymentUrl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository
    ) {
        $this->merchantKey = core()->getConfigData('sales.payment_methods.payu.merchant_key');
        $this->merchantSalt = core()->getConfigData('sales.payment_methods.payu.merchant_salt');
        $this->isSandbox = (bool) core()->getConfigData('sales.payment_methods.payu.sandbox');

        // Set payment URL based on mode
        $this->paymentUrl = $this->isSandbox
            ? 'https://test.payu.in/_payment'
            : 'https://secure.payu.in/_payment';
    }

    /**
     * Redirect to PayU payment gateway
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        // Check credentials
        if (empty($this->merchantKey) || empty($this->merchantSalt)) {
            session()->flash('error', trans('payu::app.provide-credentials'));

            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! $cart) {
            session()->flash('error', trans('payu::app.cart-not-found'));

            return redirect()->route('shop.checkout.cart.index');
        }

        // Prepare payment data
        $txnid = uniqid('PAYU_');
        $amount = round($cart->base_grand_total, 2);
        $productinfo = 'Order #' . $cart->id;
        $firstname = $cart->customer_first_name;
        $email = $cart->customer_email;
        $phone = $cart->billing_address->phone ?? '';
        $surl = route('payu.success');
        $furl = route('payu.failure');
        $curl = route('payu.cancel');

        // Generate hash
        $hashString = $this->merchantKey . '|' . $txnid . '|' . $amount . '|' . $productinfo . '|' . $firstname . '|' . $email . '|||||||||||' . $this->merchantSalt;
        $hash = strtolower(hash('sha512', $hashString));

        // Store transaction ID in session for verification
        session()->put('payu_txnid', $txnid);
        session()->put('payu_cart_id', $cart->id);

        $paymentData = [
            'key'          => $this->merchantKey,
            'txnid'        => $txnid,
            'amount'       => $amount,
            'productinfo'  => $productinfo,
            'firstname'    => $firstname,
            'email'        => $email,
            'phone'        => $phone,
            'surl'         => $surl,
            'furl'         => $furl,
            'curl'         => $curl,
            'hash'         => $hash,
        ];

        return view('payu::checkout.redirect', [
            'paymentUrl'  => $this->paymentUrl,
            'paymentData' => $paymentData,
        ]);
    }

    /**
     * Handle payment success callback
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        $response = request()->all();

        // Verify hash
        if (!$this->verifyHash($response)) {
            session()->flash('error', trans('payu::app.hash-mismatch'));
            return redirect()->route('shop.checkout.cart.index');
        }

        // Verify transaction ID
        $sessionTxnId = session()->get('payu_txnid');
        if ($sessionTxnId !== $response['txnid']) {
            session()->flash('error', trans('payu::app.invalid-transaction'));
            return redirect()->route('shop.checkout.cart.index');
        }

        try {
            // Get cart
            $cartId = session()->get('payu_cart_id');
            $cart = Cart::getCart();

            if (!$cart || $cart->id != $cartId) {
                session()->flash('error', trans('payu::app.cart-not-found'));
                return redirect()->route('shop.checkout.cart.index');
            }

            // Create order
            Cart::collectTotals();
            $data = (new OrderResource($cart))->jsonSerialize();
            
            // Add payment info
            $data['payment']['additional'] = [
                'payu_txnid'     => $response['txnid'],
                'payu_mihpayid'  => $response['mihpayid'] ?? '',
                'payu_mode'      => $response['mode'] ?? '',
                'payu_status'    => $response['status'] ?? '',
            ];

            $order = $this->orderRepository->create($data);

            // Update order status
            $this->orderRepository->update(['status' => 'processing'], $order->id);

            // Create invoice if possible
            if ($order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData($order));
            } 

            // Clear cart and session
            Cart::deActivateCart();
            session()->forget(['payu_txnid', 'payu_cart_id']);

            session()->flash('order', $order);
            session()->flash('success', trans('payu::app.payment-success'));

            return redirect()->route('shop.checkout.onepage.success');
        } catch (\Exception $e) {
            session()->flash('error', trans('payu::app.order-creation-failed'));

            return redirect()->route('shop.checkout.cart.index');
        }
    }

    /**
     * Handle payment failure callback
     *
     * @return \Illuminate\Http\Response
     */
    public function failure()
    {
        $response = request()->all();
        
        session()->forget(['payu_txnid', 'payu_cart_id']);
        session()->flash('error', trans('payu::app.payment-failed'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Handle payment cancel callback
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        $response = request()->all();
        
        session()->forget(['payu_txnid', 'payu_cart_id']);
        session()->flash('warning', trans('payu::app.payment-cancelled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Verify PayU hash
     *
     * @param array $response
     * @return bool
     */
    protected function verifyHash($response)
    {
        $status      = $response['status'] ?? '';
        $firstname   = $response['firstname'] ?? '';
        $amount      = $response['amount'] ?? '';
        $txnid       = $response['txnid'] ?? '';
        $key         = $response['key'] ?? '';
        $productinfo = $response['productinfo'] ?? '';
        $email       = $response['email'] ?? '';
        $receivedHash = $response['hash'] ?? '';

        // Build hash string according to PayU documentation
        $hashString = $key . '|' . $txnid . '|' . $amount . '|' . $productinfo . '|' . 
                    $firstname . '|' . $email . '|||||||||||' . $status . '|' . $this->merchantSalt;

        $calculatedHash = strtolower(hash('sha512', $hashString));

        return $calculatedHash === $receivedHash;
    }


    /**
     * Prepare invoice data
     *
     * @param  object  $order
     * @return array
     */
    protected function prepareInvoiceData($order)
    {
        $invoiceData = ['order_id' => $order->id];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}