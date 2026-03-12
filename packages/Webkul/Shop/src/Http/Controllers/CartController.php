<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\Cart;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductFlat;

class CartController extends Controller
{
    public function index()
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        return view('shop::checkout.cart.index');
    }

    // cart index page for guest user and logeed in user
    public function indexCart()
    {
        $logged_id = Auth::check() ? Auth::id() : null;

        $cartItems = collect();
        $subtotal = 0;
        $discount = 0;
        $total = 0;

        // LOGGED IN USER
        if ($logged_id) {

            $carts = Cart::where('customer_id', $logged_id)->get();

            foreach ($carts as $cart) {
                $cartItems = $cartItems->merge(
                    $cart->items()->with(['product', 'professional'])->get()
                );
            }
        }

        // GUEST USER
        else {

            $cartId = session()->get('guest_cart_id');

            if ($cartId) {

                $cart = Cart::find($cartId);

                if ($cart) {
                    $cartItems = $cart->items()->with(['product', 'professional'])->get();
                }
            }
        }

        // CALCULATE TOTALS
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->product->price ?? 0);
        });

        $total = $subtotal - $discount;

        return view('shop::cart.index', compact('cartItems', 'subtotal', 'discount', 'total'));
    }


    public function addToCart(Request $req, $slug)
    {
        $product = ProductFlat::where('url_key', $slug)->firstOrFail();
        $product_type = $product->type;
        $qty = $req->quantity ?? 1;
        $total = $product->price * $qty;
        $professional_id = $req->professional_id ?? null;

        $logged_id = Auth::check() ? Auth::id() : null;

        // GUEST USER
        if (!$logged_id) {

            // check cart in session
            $cartId = session()->get('guest_cart_id');

            if ($cartId) {
                $cart = Cart::find($cartId);
            }

            // if cart not exist create new
            if (!isset($cart)) {

                $cart = Cart::create([
                    'customer_id'   => null,
                    'is_guest'      => 1,
                    'channel_id'    => core()->getCurrentChannel()->id,
                    'currency_code' => core()->getCurrentCurrencyCode(),
                    'items_qty'     => 0,
                    'sub_total'     => 0,
                    'grand_total'   => 0,
                ]);

                session()->put('guest_cart_id', $cart->id);
            }

            // create cart item
            CartItem::create([
                'cart_id'        => $cart->id,
                'product_id'     => $product->product_id,
                'quantity'       => $qty,
                'price'          => $product->price,
                'base_price'     => $product->price,
                'total'          => $total,
                'base_total'     => $total,
                'type'           => $product_type,
                'professional_id' => $professional_id
            ]);

            // update cart totals
            $cart->items_qty += $qty;
            $cart->sub_total += $total;
            $cart->grand_total += $total;
            $cart->save();

            return redirect()->route('shop.cart.index')
                ->with('success', 'Product added to cart');
        } else {

            // check cart in session
            $cartId = session()->get('guest_cart_id');

            if ($cartId) {
                $cart = Cart::find($cartId);
            }

            // if cart not exist create new
            if (!isset($cart)) {

                $cart = Cart::create([
                    'customer_id'   => null,
                    'is_guest'      => 1,
                    'channel_id'    => core()->getCurrentChannel()->id,
                    'currency_code' => core()->getCurrentCurrencyCode(),
                    'items_qty'     => 0,
                    'sub_total'     => 0,
                    'grand_total'   => 0,
                ]);

                session()->put('guest_cart_id', $cart->id);
            }

            // create cart item
            CartItem::create([
                'cart_id'        => $cart->id,
                'product_id'     => $product->product_id,
                'quantity'       => $qty,
                'price'          => $product->price,
                'base_price'     => $product->price,
                'total'          => $total,
                'base_total'     => $total,
                'type'           => $product_type,
                'professional_id' => $professional_id
            ]);

            // update cart totals
            $cart->items_qty += $qty;
            $cart->sub_total += $total;
            $cart->grand_total += $total;
            $cart->save();

            return redirect()->route('shop.cart.index')
                ->with('success', 'Product added to cart');

        }
    }


    public function removeCartItem($id)
    {
        $item = CartItem::findOrFail($id);

        $cart = $item->cart;

        $cart->items_qty -= $item->quantity;
        $cart->sub_total -= $item->total;
        $cart->grand_total -= $item->total;

        $cart->save();

        $item->delete();

        return back()->with('success', 'Item removed from cart');

    }
}
