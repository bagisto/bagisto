<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Cart;

/**
 * Cart controller for the customer and guest users for adding and
 * removing the products in the cart.
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @author  Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * WishlistRepository Repository object
     *
     * @var Object
     */
    protected $wishlistRepository;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CartItemRepository $wishlistRepository
     * @param  \Webkul\Product\Repositories\ProductRepository   $productRepository
     * @return void
     */
    public function __construct(
        WishlistRepository $wishlistRepository,
        ProductRepository $productRepository
    )
    {
        $this->middleware('customer')->only(['moveToWishlist']);

        $this->wishlistRepository = $wishlistRepository;

        $this->productRepository = $productRepository;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the cart page which will be populated before the checkout process.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Cart::collectTotals();

        return view($this->_config['view'])->with('cart', Cart::getCart());
    }

    /**
     * Function for guests user to add the product in the cart.
     *
     * @return Mixed
     */
    public function add($id)
    {
        try {
            $result = Cart::addProduct($id, request()->all());

            if (is_array($result) && isset($result['warning'])) {
                session()->flash('warning', $result['warning']);
            } else {
                session()->flash('success', trans('shop::app.checkout.cart.item.success'));

                if ($customer = auth()->guard('customer')->user())
                    $this->wishlistRepository->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);

                if (request()->get('is_buy_now'))
                    return redirect()->route('shop.checkout.onepage.index');
            }
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            $product = $this->productRepository->find($id);

            return redirect()->route('shop.productOrCategory.index', ['slug' => $product->url_key]);
        }

        return redirect()->back();
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param integer $itemId
     * @return Response
     */
    public function remove($itemId)
    {
        $result = Cart::removeItem($itemId);

        if ($result)
            session()->flash('success', trans('shop::app.checkout.cart.item.success-remove'));

        return redirect()->back();
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return Response
     */
    public function updateBeforeCheckout()
    {
        try {
            $result = Cart::updateItems(request()->all());

            if ($result)
                session()->flash('success', trans('shop::app.checkout.cart.quantity.success'));
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));
        }

        return redirect()->back();
    }

    /**
     * Function to move a already added product to wishlist will run only on customer authentication.
     *
     * @param integer $id
     * @return Response
     */
    public function moveToWishlist($id)
    {
        $result = Cart::moveToWishlist($id);

        if ($result) {
            session()->flash('success', trans('shop::app.checkout.cart.move-to-wishlist-success'));
        } else {
            session()->flash('warning', trans('shop::app.checkout.cart.move-to-wishlist-error'));
        }

        return redirect()->back();
    }

    /**
     * Apply coupon to the cart
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function applyCoupon()
    {
        $couponCode = request()->get('code');

        try {
            if (strlen($couponCode)) {
                Cart::setCouponCode($couponCode)->collectTotals();

                if (Cart::getCart()->coupon_code == $couponCode) {
                    return response()->json([
                        'success' => true,
                        'message' => trans('shop::app.checkout.total.success-coupon')
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.invalid-coupon')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.coupon-apply-issue')
            ]);
        }
    }

    /**
     * Apply coupon to the cart
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function removeCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        return response()->json([
            'success' => true,
            'message' => trans('shop::app.checkout.total.remove-coupon')
        ]);
    }
}