<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Checkout\Contracts\Cart as CartModel;
use Illuminate\Support\Facades\Event;
use Cart;

class CartController extends Controller
{
    /**
     * WishlistRepository Repository object
     *
     * @var \Webkul\Customer\Repositories\WishlistRepository
     */
    protected $wishlistRepository;

    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * OrderrRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CartItemRepository  $wishlistRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Order\Repositories\OrderRepository  $orderRepository
     * @return void
     */
    public function __construct(
        WishlistRepository $wishlistRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
    )
    {
        $this->middleware('customer')->only(['moveToWishlist','reorder']);

        $this->wishlistRepository = $wishlistRepository;

        $this->productRepository = $productRepository;

        $this->orderRepository = $orderRepository;

        parent::__construct();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function add($id)
    {
        try {
            $result = Cart::addProduct($id, request()->all());

            if ($this->onWarningAddingToCart($result)) {
                session()->flash('warning', $result['warning']);

                return redirect()->back();
            }

            if ($result instanceof CartModel) {
                session()->flash('success', trans('shop::app.checkout.cart.item.success'));

                if ($customer = auth()->guard('customer')->user()) {
                    $this->wishlistRepository->deleteWhere(['product_id' => $id, 'customer_id' => $customer->id]);
                }

                if (request()->get('is_buy_now')) {
                    Event::dispatch('shop.item.buy-now', $id);

                    return redirect()->route('shop.checkout.onepage.index');
                }
            }
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            $product = $this->productRepository->find($id);

            return redirect()->route('shop.productOrCategory.index', $product->url_key);
        }

        return redirect()->back();
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function remove($itemId)
    {
        $result = Cart::removeItem($itemId);

        if ($result) {
            session()->flash('success', trans('shop::app.checkout.cart.item.success-remove'));
        }

        return redirect()->back();
    }

    /**
     * Re add products to the cart according to order id
     *
     * @return \Illuminate\Http\Response
    */
    public function reorder($orderId)
    {
        $order = $this->orderRepository->findOneWhere([
            'customer_id' => auth()->guard('customer')->user()->id,
            'id'          => $orderId,
        ]);

        foreach ($order->items as $item) {
            $slugOrPath = strtolower(str_replace(" ","-",$item->name));
            $product = $this->productRepository->findBySlug($slugOrPath);
            Cart::addProduct($product->product_id, ['product_id'=>$product->product_id,'quantity'=>$item->qty_ordered]); 
        }

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateBeforeCheckout()
    {
        try {
            $result = Cart::updateItems(request()->all());

            if ($result) {
                session()->flash('success', trans('shop::app.checkout.cart.quantity.success'));
            }
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));
        }

        return redirect()->back();
    }

    /**
     * Function to move a already added product to wishlist will run only on customer authentication.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
                        'message' => trans('shop::app.checkout.total.success-coupon'),
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.invalid-coupon'),
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.coupon-apply-issue'),
            ]);
        }
    }

    /**
     * Apply coupon to the cart
     *
     * @return \Illuminate\Http\Response
    */
    public function removeCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        return response()->json([
            'success' => true,
            'message' => trans('shop::app.checkout.total.remove-coupon'),
        ]);
    }

    /**
     * Returns true, if result of adding product to cart is an array and contains a key "warning"
     *
     * @param  array  $result
     * @return boolean
     */
    private function onWarningAddingToCart($result): bool
    {
        return is_array($result) && isset($result['warning']);
    }
}
