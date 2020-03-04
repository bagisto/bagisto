<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Payment\Facades\Payment;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Customer\Repositories\CustomerRepository;

/**
 * Chekout controller for the customer and guest for placing order
 *
 * @author  Jitendra Singh <jitendra@webkul.com> @jitendra-webkul
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OnepageController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

     /**
     * customerRepository instance object
     */
    protected $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository   $orderRepository
     * @param  \Webkul\Customer\Repositories\CustomerRepository $customerRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->customerRepository = $customerRepository;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        if (! auth()->guard('customer')->check() && ! core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout')) {
            return redirect()->route('customer.session.index');
        }

        if (Cart::hasError())
            return redirect()->route('shop.checkout.cart.index');

        $cart = Cart::getCart();

        if (! auth()->guard('customer')->check() && $cart->hasDownloadableItems()) {
            return redirect()->route('customer.session.index');
        }

        if (! auth()->guard('customer')->check() && ! $cart->hasGuestCheckoutItems()) {
            return redirect()->route('customer.session.index');
        }

        Cart::collectTotals();

        return view($this->_config['view'], compact('cart'));
    }

    /**
     * Return order short summary
     *
     * @return \Illuminate\View\View
    */
    public function summary()
    {
        $cart = Cart::getCart();

        return response()->json([
            'html' => view('shop::checkout.total.summary', compact('cart'))->render(),
        ]);
    }

    /**
     * Saves customer address.
     *
     * @param  \Webkul\Checkout\Http\Requests\CustomerAddressForm $request
     * @return \Illuminate\Http\Response
    */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = request()->all();

        if (! auth()->guard('customer')->check() && ! Cart::getCart()->hasGuestCheckoutItems()) {
            return response()->json(['redirect_url' => route('customer.session.index')], 403);
        }

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (Cart::hasError() || ! Cart::saveCustomerAddress($data)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        } else {
            $cart = Cart::getCart();

            Cart::collectTotals();

            if ($cart->haveStockableItems()) {
                if (! $rates = Shipping::collectRates()) {
                    return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
                } else {
                    return response()->json($rates);
                }
            } else {
                return response()->json(Payment::getSupportedPaymentMethods());
            }
        }
    }

    /**
     * Saves shipping method.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveShipping()
    {
        $shippingMethod = request()->get('shipping_method');

        if (Cart::hasError() || !$shippingMethod || !Cart::saveShippingMethod($shippingMethod)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * Saves payment method.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function savePayment()
    {
        $payment = request()->get('payment');

        if (Cart::hasError() || ! $payment || ! Cart::savePaymentMethod($payment)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'jump_to_section' => 'review',
            'html'            => view('shop::checkout.onepage.review', compact('cart'))->render(),
        ]);
    }

    /**
     * Saves order.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveOrder()
    {
        if (Cart::hasError()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $this->validateOrder();

        $cart = Cart::getCart();

        if ($redirectUrl = Payment::getRedirectUrl($cart)) {
            return response()->json([
                'success'      => true,
                'redirect_url' => $redirectUrl,
            ]);
        }

        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        Cart::deActivateCart();

        session()->flash('order', $order);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Order success page
     *
     * @return \Illuminate\Http\Response
    */
    public function success()
    {
        if (! $order = session('order')) {
            return redirect()->route('shop.checkout.cart.index');
        }

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Validate order before creation
     *
     * @return mixed
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        if ($cart->haveStockableItems() && ! $cart->shipping_address) {
            throw new \Exception(trans('Please check shipping address.'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('Please check billing address.'));
        }

        if ($cart->haveStockableItems() && ! $cart->selected_shipping_rate) {
            throw new \Exception(trans('Please specify shipping method.'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('Please specify payment method.'));
        }
    }

    /**
     * Check Customer is exist or not
     *
     * @return Response
     */
    public function checkExistCustomer()
    {
       $customer = $this->customerRepository->findOneWhere([
            'email' => request()->email,
       ]);

       if (! is_null($customer)) {
           return 'true';
       }

       return 'false';
    }

    /**
     * Login for checkout
     *
     * @return Response
     */
    public function loginForCheckout()
    {
        $this->validate(request(), [
            'email' => 'required|email'
        ]);

        if (! auth()->guard('customer')->attempt(request(['email', 'password']))) {
            return response()->json(['error' => trans('shop::app.customer.login-form.invalid-creds')]);
        }

        Cart::mergeCart();

        return response()->json(['success' => 'Login successfully']);
    }

    /**
     * To apply couponable rule requested
     *
     * @return JSON
     */
    public function applyCoupon()
    {
        $this->validate(request(), [
            'code' => 'string|required',
        ]);

        $code = request()->input('code');

        $result = $this->coupon->apply($code);

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('shop::app.checkout.total.coupon-applied'),
                'result'  => $result,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.cannot-apply-coupon'),
                'result'  => null,
            ], 422);
        }

        return $result;
    }

    /**
     * Initiates the removal of couponable cart rule
     *
     * @return Void
     */
    public function removeCoupon()
    {
        $result = $this->coupon->remove();

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('admin::app.promotion.status.coupon-removed'),
                'data'    => [
                    'grand_total' => core()->currency(Cart::getCart()->grand_total),
                ],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('admin::app.promotion.status.coupon-remove-failed'),
                'data'    => null,
            ], 422);
        }
    }
}