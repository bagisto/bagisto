<?php

namespace Webkul\Velocity\Http\Controllers\Shop;

use Cart;
use Webkul\Velocity\Helpers\Helper;
use Webkul\Checkout\Contracts\Cart as CartModel;
use Webkul\Product\Repositories\ProductRepository;

class CartController extends Controller
{
    /**
     * Retrives the mini cart details
     *
     * @return \Illuminate\Http\Response
    */
    public function getMiniCartDetails()
    {
        $cart = cart()->getCart();

        if ($cart) {
            $items = $cart->items;
            $cartItems = $items->toArray();

            $cartDetails = [];
            $cartDetails['base_sub_total'] = core()->currency($cart->base_sub_total);

            foreach ($items as $index => $item) {
                $images = $item->product->getTypeInstance()->getBaseImage($item);

                $cartItems[$index]['images'] = $images;
                $cartItems[$index]['url_key'] = $item->product->url_key;
                $cartItems[$index]['base_total'] = core()->currency($item->base_total);
            }

            $response = [
                'status'    => true,
                'mini_cart' => [
                    'cart_items' => $cartItems,
                    'cart_details' => $cartDetails,
                ],
            ];
        } else {
            $response = [
                'status' => false,
            ];
        }

        return response()->json($response, 200);
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @return array
    */
    public function addProductToCart()
    {
        try {
            $cart = Cart::getCart();
            $id = request()->get('product_id');

            $cart = Cart::addProduct($id, request()->all());

            if (is_array($cart) && isset($cart['warning'])) {
                $response = [
                    'status'  => 'warning',
                    'message' => $cart['warning'],
                ];
            }

            if ($cart instanceof CartModel) {
                $formattedItems = [];

                foreach ($cart->items as $item) {
                    array_push($formattedItems, $this->velocityHelper->formatCartItem($item));
                }

                $response = [
                    'status'         => 'success',
                    'totalCartItems' => sizeof($cart->items),
                    'message'        => trans('shop::app.checkout.cart.item.success'),
                ];

                if ($customer = auth()->guard('customer')->user()) {
                    app('Webkul\Customer\Repositories\WishlistRepository')->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);
                }

                if (request()->get('is_buy_now')) {
                    return redirect()->route('shop.checkout.onepage.index');
                }
            }
        } catch(\Exception $exception) {
            $product = $this->productRepository->find($id);

            $response = [
                'status'           => 'danger',
                'message'          => trans($exception->getMessage()),
                'redirectionRoute' => route('shop.productOrCategory.index', $product->url_key),
            ];
        }

        return $response ?? [
            'status'  => 'danger',
            'message' => trans('velocity::app.error.something_went_wrong'),
        ];
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
    */
    public function removeProductFromCart($itemId)
    {
        $result = Cart::removeItem($itemId);

        if ($result) {
            $response = [
                'status'  => 'success',
                'label'   => trans('velocity::app.shop.general.alert.success'),
                'message' => trans('shop::app.checkout.cart.item.success-remove'),
            ];
        }

        return response()->json($response ?? [
            'status'  => 'danger',
            'label'   => trans('velocity::app.shop.general.alert.error'),
            'message' => trans('velocity::app.error.something_went_wrong'),
        ], 200);
    }
}