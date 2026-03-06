<?php

namespace Webkul\Shop\Http\Controllers;
use Illuminate\Http\Request;
use Webkul\Product\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\Cart;


class CartController extends Controller
{

    public function index()
    {
        if (! core()->getConfigData('sales.checkout.shopping_cart.cart_page')) {
            abort(404);
        }

        return view('shop::checkout.cart.index');
    }

    public function indexCart(){
        return view('shop::cart.index');
    }

    public function addToCart(Request $req)
    {

    $user = Auth::user();

    $cart = Cart::create([
    'customer_id' => auth()->id(),
    'customer_email' => $user->email ?? '',
    'customer_first_name' => $user->first_name ?? '',
    'customer_last_name' => $user->last_name ?? '',
    'channel_id' => core()->getCurrentChannel()->id,
    'currency_code' => core()->getCurrentCurrencyCode(),
    ]);

    $CartItem = CartItem::create([
    'cart_id' => $cart->id,
    'product_id' => $req->product_id,
    'quantity' => $req->quantity,
    'type' => 'booking',
    'additional' => json_encode([
        'pending_booking' => true
    ]),
    ]);

    if($cart && $CartItem){
       return redirect()->route('shop.cart.index')->with('success','Service Added to Cart Successfully');
    }else{
        return redirect()->route('shop.cart.index')->with('falied','Somthing Went Wrong, Try Again');
    }
    }   
}
