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
                    $cart->items()->with('product')
                        ->whereHas('product', fn ($q) => $q->where('type', 'simple'))
                        ->paginate(3)
                );
            }
        }

        // GUEST USER
        else {
            $cartId = session()->get('guest_cart_id');
            if ($cartId) {
                $cart = Cart::find($cartId);
                if ($cart) {
                    $cartItems = $cart->items()->with('product')
                        ->whereHas('product', fn ($q) => $q->where('type', 'simple'))
                        ->paginate(3);
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


    // adding items to cart for guest and logged in user
    public function addToCart(Request $req, $slug)
    {
        $product = ProductFlat::where('url_key', $slug)->firstOrFail();
        $product_type = $product->type;
        $qty = $req->quantity ?? 1;
        $total = $product->price * $qty;
        $logged_id = Auth::check() ? Auth::id() : null;

        // --- Get or create cart ---
        $cartId = session()->get('guest_cart_id');
        $cart = null;

        if ($logged_id) {
            // Logged-in user: try to get cart by customer
            $cart = Cart::where('customer_id', $logged_id)->first();
        } elseif ($cartId) {
            // Guest user: try session cart
            $cart = Cart::find($cartId);
        }

        // If cart does not exist, create a new one
        if (!isset($cart)) {
            $cart = Cart::create([
                'customer_id'   => $logged_id,
                'is_guest'      => $logged_id ? 0 : 1,
                'channel_id'    => core()->getCurrentChannel()->id,
                'currency_code' => core()->getCurrentCurrencyCode(),
                'items_qty'     => 0,
                'sub_total'     => 0,
                'grand_total'   => 0,
            ]);

            if (!$logged_id) {
                session()->put('guest_cart_id', $cart->id);
            }
        }

        // --- Check if product already exists in cart ---
        $cartItem = $cart->items()->where('product_id', $product->product_id)->first();

        if ($cartItem) {
            // Product exists: increment quantity and update totals
            $cartItem->quantity += $qty;
            $cartItem->total = $cartItem->price * $cartItem->quantity;
            $cartItem->base_total = $cartItem->total;
            $cartItem->save();
        } else {
            // Product does not exist: create new cart item
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $product->product_id,
                'quantity'   => $qty,
                'price'      => $product->price,
                'base_price' => $product->price,
                'total'      => $total,
                'base_total' => $total,
                'type'       => $product_type,
            ]);
        }

        // --- Update cart totals ---
        $cart->items_qty = $cart->items()->sum('quantity');
        $cart->sub_total = $cart->items()->sum('total');
        $cart->grand_total = $cart->sub_total;
        $cart->save();

        return redirect()->route('shop.cart.index')
            ->with('success', 'Product added to cart');
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
