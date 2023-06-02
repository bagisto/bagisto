<?php

namespace Webkul\Shop\Http\Controllers\Api\Checkout;

use Cart;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Shop\Http\Resources\CartResource;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CartItemRepository  $wishlistRepository
     * @return void
     */
    public function __construct(
        protected WishlistRepository $wishlistRepository,
    ) {
    }

    /**
     * Function for get the cart product.
     *
     * @return array
     */
    public function index()
    {
        Cart::collectTotals();

        $cart = Cart::getCart();

        return CartResource::collection($cart);
    }

    /**
     * Function for remove single cart product.
     *
     * @return \Illuminate\View\View
     */
    public function destroy($id) {
        Cart::removeItem($id)
            ? session()->flash('success', trans('shop::app.components.mini-cart.item.success-remove'))
            : session()->flash('success', trans('shop::app.components.mini-cart.item.warning-remove'));

        return redirect()->back();
    }

    /**
     * Function for user to add the product in the cart.
     *
     * @return array
     */
    public function store()
    {
        try {
            $productId = request()->input('product_id');

            Cart::deactivateCurrentCartIfBuyNowIsActive();

            $cart = Cart::addProduct($productId, request()->all());
    
            if (
                is_array($cart)
                && isset($cart['warning'])
            ) {
                return response()->json([
                    'message' => $cart['warning'],
                ]);
            }
    
            if ($cart) {
                if ($customer = auth()->guard('customer')->user()) {
                    $this->wishlistRepository->deleteWhere([
                        'product_id' => $productId, 
                        'customer_id' => $customer->id
                    ]);
                }

                return response()->json([
                    'message'  => trans('shop::app.components.products.item-add-to-cart')
                ]);
            }
        } catch (\Exception $exception) {
            \Log::error('CartController: ' . $exception->getMessage(),
                ['product_id' => $productId, 'cart_id' => cart()->getCart() ?? 0]);

            return response()->json([
                'message'   => $exception->getMessage()
            ]);
        }
    }
}
