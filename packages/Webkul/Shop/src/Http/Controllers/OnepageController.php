<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Checkout\Facades\Cart;
use App\Http\Controllers\Controller;
use Webkul\Shop\Http\Requests\Customer\AddressRequest;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Payment\Facades\Payment;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Sales\Repositories\OrderItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\Sales\Models\Order;

class OnepageController extends Controller
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }


    // checkout for products that are in cart
    public function checkoutProduct()
    {
        $cart = Cart::getCart();

        // check guest cart
        if (!$cart) {
            $guestCartId = session()->get('guest_cart_id');

            if ($guestCartId) {
                $cart = CartModel::find($guestCartId);

                // IMPORTANT: tell Bagisto this is the current cart
                if ($cart) {
                    Cart::setCart($cart);
                }
            }
        }

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Cart Is Empty');
        }

        $user = auth()->guard('customer')->user();

        if (!$user && !core()->getConfigData('sales.checkout.shopping_cart.allow_guest_checkout')) {
            return redirect()->route('shop.customer.session.index');
        }

        if ($user?->is_suspended) {
            session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));
            return redirect()->route('shop.checkout.cart.index');
        }

        // GET CART ITEMS
        $cartItems = $cart->items()->with(['product'])->get();

        // CALCULATE TOTALS
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->product->price ?? 0);
        });

        $discount = 0;

        $tax = $subtotal * 0.05;

        $total = $subtotal + $tax - $discount;

        // IMPORTANT: collect totals for payment methods
        Cart::collectTotals();

        // LOAD PAYMENT METHODS
        $methods = Payment::getSupportedPaymentMethods();

        return view('shop::checkout.onepage.product_checkout', compact(
            'cartItems',
            'subtotal',
            'discount',
            'tax',
            'total',
            'methods'
        ));
    }

    public function checkoutProductFinal(Request $request)
    {
        $cart = Cart::getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Cart is empty');
        }

        $billing  = $request->billing ?? [];
        $shipping = ($request->same_as_billing ?? false) ? $billing : ($request->shipping ?? []);

        // Safely handle address arrays
        $billingAddress  = is_array($billing['address'] ?? null) ? $billing['address'] : [];
        $shippingAddress = is_array($shipping['address'] ?? null) ? $shipping['address'] : [];

        $params = [
            'billing' => [
                'first_name' => $billing['first_name'] ?? '',
                'last_name'  => $billing['last_name'] ?? '',
                'email'      => $billing['email'] ?? '',
                'phone'      => $billing['phone'] ?? '',
                'address'    => [
                    $billingAddress[0] ?? '',
                    $billingAddress[1] ?? '',
                ],
                'city'     => $billing['city'] ?? '',
                'state'    => $billing['state'] ?? '',
                'country'  => $billing['country'] ?? '',
                'postcode' => $billing['postcode'] ?? '',
            ],

            'shipping' => [
                'first_name' => $shipping['first_name'] ?? '',
                'last_name'  => $shipping['last_name'] ?? '',
                'email'      => $shipping['email'] ?? '',
                'phone'      => $shipping['phone'] ?? '',
                'address'    => [
                    $shippingAddress[0] ?? '',
                    $shippingAddress[1] ?? '',
                ],
                'city'     => $shipping['city'] ?? '',
                'state'    => $shipping['state'] ?? '',
                'country'  => $shipping['country'] ?? '',
                'postcode' => $shipping['postcode'] ?? '',
            ],
        ];

        // Save addresses
        Cart::saveAddresses($params);

        // Save payment method safely
        $paymentMethod = $request->payment['method'] ?? null;
        if ($paymentMethod) {
            Cart::savePaymentMethod(['method' => $paymentMethod]);
        }



        $orderData = [
            'customer_id' => $cart->customer_id,
            'cart_id'     => $cart->id,
            'grand_total' => $cart->grand_total,
            'sub_total'   => $cart->sub_total,
            'discount'    => $cart->sub_total - $cart->grand_total,
            'currency_code' => $cart->currency_code,
            'channel_id'  => $cart->channel_id,
            'payment'     => $request->payment,          // same structure as repository expects
            'billing_address'  => $params['billing'],
            'shipping_address' => $params['shipping'],
            'items'       => $cart->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'price'      => $item->price,
                    'quantity'   => $item->quantity,
                    'total'      => $item->total,
                    'type'       => $item->type,
                ];
            })->toArray(),
        ];

        $order = app(\Webkul\Sales\Repositories\OrderRepository::class)
                 ->createOrderIfNotThenRetry($orderData);

        // Clear cart
        $cart->items()->delete();
        $cart->delete();

        return redirect()->route('shop.checkout.success')
                 ->with('order_id', $order->increment_id);
    }

    public function productcheckoutSuccess()
    {
        $order = null;

        if (Auth::check()) {
            // Logged-in user: get latest order
            $order = Order::where('customer_id', Auth::id())
                          ->latest()
                          ->first();
        } else {
            // Guest user: get latest order from session
            $guestOrderId = session()->get('guest_order_id');
            if ($guestOrderId) {
                $order = Order::find($guestOrderId);
                // optionally remove it from session after fetching
                session()->forget('guest_order_id');
            }
        }

        if (!$order) {
            return redirect()->route('sbt.perfume.index')
                             ->with('error', 'No recent order found.');
        }

        // Pass order data to the view
        return view('shop::checkout.success', [
            'orderId' => $order->id,
            'message' => 'Your order has been placed successfully!',
        ]);
    }


}
