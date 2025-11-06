<?php

namespace Webkul\Stripe\Http\Controllers;

use Stripe\PaymentIntent;
use Stripe\Stripe;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Transformers\OrderResource;
use Webkul\Stripe\Helpers\Helper;
use Webkul\Stripe\Repositories\StripeCartRepository as StripeCart;
use Webkul\Stripe\Repositories\StripeRepository;

class StripeConnectController extends Controller
{
    /**
     * To hold the Test stripe secret key
     */
    protected $stripeSecretKey = null;

    /**
     * To hold the Test stripe secret key
     */
    protected $stripePublishableKey = null;

    /**
     * Determine test mode
     */
    protected $testMode;

    /**
     * Determine if Stripe is active or Not
     */
    protected $active;

    /**
     * Statement descriptor string
     */
    protected $statementDescriptor;

    /**
     * InvoiceRepository object
     *
     * @var object
     */
    protected $invoiceRepository;

    /**
     * App Name
     *
     * @var string
     */
    protected $appName;

    /**
     * App Name
     *
     * @var string
     */
    protected $partner_Id;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected StripeCart $stripeCart,
        protected stripeRepository $stripeRepository,
        protected Helper $helper
    ) {
        $this->testMode = (bool) core()->getConfigData('sales.payment_methods.stripe.debug');

        if ($this->testMode) {
            $this->stripeSecretKey = core()->getConfigData('sales.payment_methods.stripe.api_test_key');
            $this->stripePublishableKey = core()->getConfigData('sales.payment_methods.stripe.api_test_publishable_key');
        } else {
            $this->stripeSecretKey = core()->getConfigData('sales.payment_methods.stripe.api_key');
            $this->stripePublishableKey = core()->getConfigData('sales.payment_methods.stripe.api_publishable_key');
        }

        $this->appName = 'Webkul Bagisto Stripe Payment Gateway';

        $this->partner_Id = 'pp_partner_FLJSvfbQDaJTyY';

        Stripe::setApiVersion('2019-12-03');

        Stripe::setAppInfo(
            $this->appName,
            env('APP_VERSION'),
            env('APP_URL'),
            $this->partner_Id
        );

        stripe::setApiKey($this->stripeSecretKey);
    }

    /**
     * Redirects to the stripe.
     *
     * @return \Illuminate\View\View
     */
    public function redirect()
    {
        if (
            empty($this->stripeSecretKey) ||
            empty($this->stripePublishableKey)
        ) {
            session()->flash('error', trans('stripe::app.provide-api-key'));

            return redirect()->route('shop.checkout.cart.index');
        } else {
            return view('stripe::checkout.card');
        }
    }

    /**
     * Save card after payment using new card.
     *
     * @return Json
     */
    public function saveCard()
    {
        try {
            $customerResponse = \Stripe\Customer::create([
                'description' => 'Customer for '.Cart::getCart()->customer_email,
                'source'      => request()->stripetoken, // obtained with Stripe.js
            ]);

            $payment_method = \Stripe\PaymentMethod::retrieve(request()->paymentMethodId);

            $attachedCustomer = $payment_method->attach(['customer' => $customerResponse->id]);

            $last4 = request()->result['paymentMethod']['card']['last4'];

            $response = [
                'customerResponse' => $customerResponse,
                'attachedCustomer' => $attachedCustomer,
            ];

            if (auth()->guard('customer')->check()) {
                $getStripeRepository = $this->stripeRepository->findOneWhere([
                    'last_four'   => $last4,
                    'customer_id' => auth()->guard('customer')->user()->id,
                ]);

                if (isset($getStripeRepository)) {
                    $getStripeRepository->update(['misc' => json_encode($response)]);
                } elseif (request()->isSavedCard) {
                    $this->stripeRepository->create([
                        'customer_id' => auth()->guard('customer')->user()->id,
                        'token'       => request()->stripetoken,
                        'last_four'   => $last4,
                        'misc'        => json_encode($response),
                    ]);
                }

                $this->stripeCart->create([
                    'cart_id'      => Cart::getCart()->id,
                    'stripe_token' => json_encode($response),
                ]);
            } else {
                $this->stripeCart->create([
                    'cart_id'      => Cart::getCart()->id,
                    'stripe_token' => json_encode($response),
                ]);
            }

            return response()->json([
                'customerId'      => $customerResponse->id,
                'paymentMethodId' => request()->paymentMethodId,
            ]);
        } catch (\Stripe\Exception\CardException $e) {

            // Since it's a decline, \Stripe\Exception\CardException will be caught
            session()->flash('error', $e->getError()->message);

            return response()->json([
                'message' => $e->getError()->message,
            ]);
        } catch (\Stripe\Exception\RateLimitException $e) {

            // Too many requests made to the API too quickly
            session()->flash('error', $e->getError()->message);

            return response()->json([
                'message' => $e->getError()->message,
            ]);
        } catch (\Stripe\Exception\InvalidRequestException $e) {

            // Invalid parameters were supplied to Stripe's API
            session()->flash('error', $e->getError()->message);

            return response()->json([
                'message' => $e->getError()->message,
            ]);
        } catch (\Stripe\Exception\AuthenticationException $e) {

            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            session()->flash('error', $e->getError()->message);

            return response()->json([
                'message' => $e->getError()->message,
            ]);
        } catch (\Stripe\Exception\ApiConnectionException $e) {

            // Network communication with Stripe failed
            session()->flash('error', $e->getError()->message);

            return response()->json([
                'message' => $e->getError()->message,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {

            // Display a very generic error to the user, and maybe send
            // yourself an email
            session()->flash('error', $e->getError()->message);

            return response()->json([
                'message' => $e->getError()->message,
            ]);
        } catch (Exception $e) {

            // Something else happened, completely unrelated to Stripe
            session()->flash('error', $e->getError()->message);

            return response()->json([
                'message' => trans('stripe::app.something-went-wrong'),
            ]);
        }
    }

    /**
     * Generate payment using saved card
     *
     * @return Json
     */
    public function savedCardPayment()
    {
        try {
            $selectedId = request()->savedCardSelectedId;

            $savedCard = $this->stripeRepository->findOneWhere([
                'id' => $selectedId,
            ]);

            $miscDecoded = json_decode($savedCard->misc);

            $payment = $this->helper->productDetail();

            $stripeId = '';

            $customerId = $miscDecoded->customerResponse->id;

            $paymentMethodId = $miscDecoded->attachedCustomer->id;

            $savedCardPayment = $this->helper->stripePayment($payment, $stripeId, $paymentMethodId, $customerId);

            if ($savedCard) {
                return response()->json([
                    'customer_id'       => $miscDecoded->customerResponse->id,
                    'payment_method_id' => $miscDecoded->attachedCustomer->id,
                    'savedCardPayment'  => $savedCardPayment,
                ]);
            }

            return response()->json(['sucess' => 'false'], 404);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Collect stripe token from client side
     *
     * @return json
     */
    public function collectToken()
    {
        $payment = $this->helper->productDetail();

        $stripeId = '';

        $stripeToken = $this->stripeCart->scopeQuery(function ($query) {
            return $query->orderBy('id', 'desc');
        })->findByField('cart_id', Cart::getCart()->id)->first()->stripe_token;

        $decodeStripeToken = json_decode($stripeToken);

        $customerId = $decodeStripeToken->customerResponse->id;

        $paymentMethodId = $decodeStripeToken->attachedCustomer->id;

        $intent = $this->helper->stripePayment($payment, $stripeId, $paymentMethodId, $customerId);

        if ($intent) {
            return response()->json(['client_secret' => $intent->client_secret]);
        } else {
            return response()->json(['success' => 'false'], 400);
        }
    }

    /**
     * Prepares order's
     *
     * @return json
     */
    public function createCharge()
    {
        Cart::collectTotals();

        $cart = Cart::getCart();

        $data = (new OrderResource($cart))->jsonSerialize();

        $order = $this->orderRepository->create($data);

        $this->orderRepository->update(['status' => 'processing'], $order->id);

        $this->invoiceRepository = app('Webkul\Sales\Repositories\InvoiceRepository');

        if ($order->canInvoice()) {
            $this->invoiceRepository->create($this->prepareInvoiceData($order));
        }

        Cart::deActivateCart();

        session()->flash('order_id', $order->id);

        return response()->json([
            'data' => [
                'route'   => route('shop.checkout.onepage.success'),
                'success' => true,
            ],
        ]);
    }

    /**
     * Prepares order's invoice data for creation
     *
     * @return array
     */
    public function prepareInvoiceData($order)
    {
        $invoiceData = [
            'order_id' => $order->id,
        ];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Delete the selected stripe card
     *
     * @return string
     */
    public function deleteCard()
    {
        $deleteIfFound = $this->stripeRepository->find(request('id'));

        $result = $deleteIfFound->delete();

        return (string) $result;
    }

    /**
     * On payment cancel
     *
     * @return response
     */
    public function paymentCancel()
    {
        session()->flash('error', trans('stripe::app.payment-failed'));

        return response()->json([
            'data' => [
                'route'   => route('shop.checkout.cart.index'),
                'success' => true,
            ],
        ]);
    }

    /**
     * On Google Pay and Apple Pay Payments Intent
     *
     * @return response
     */
    public function elementpaymentIntent()
    {
        $result = PaymentIntent::create([
            'amount'                => round(Cart::getCart()->base_grand_total, 2) * 100,
            'currency'              => core()->getBaseCurrencyCode(),
            'payment_method_types'  => ['card'],
            'receipt_email'         => Cart::getCart()->customer_email,
            'description'           => trans('stripe::app.payment-for-order-id').' - #'.Cart::getCart()->id,
            'shipping'              => [
                'name'              => Cart::getCart()->customer_first_name.' '.Cart::getCart()->customer_last_name,
                'phone'             => Cart::getCart()->billing_address->phone,
                'address'           => [
                    'city'          => Cart::getCart()->billing_address->city,
                    'country'       => Cart::getCart()->billing_address->country,
                    'line1'         => Cart::getCart()->billing_address->address1,
                    'line2'         => Cart::getCart()->billing_address->address2,
                    'postal_code'   => Cart::getCart()->billing_address->postcode,
                    'state'         => Cart::getCart()->billing_address->state,
                ],
            ],
            'metadata' => [
                'order_id' => Cart::getCart()->id,
            ],
        ]);

        $response = [
            'paymentIntent' => $result,
        ];

        $this->stripeCart->create([
            'cart_id'       => Cart::getCart()->id,
            'stripe_token'  => json_encode($response),
        ]);

        return response()->json(['success' => $result]);
    }
}
