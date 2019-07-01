<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Illuminate\Support\Facades\Event;
use Cart;

/**
 * Cart controller for the customer and guest users for adding and
 * removing the products in the cart.
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
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
     * CartItemRepository object
     *
     * @var Object
     */
    protected $cartItemRepository;

    /**
     * ProductRepository object
     *
     * @var Object
     */
    protected $productRepository;

    /**
     * WishlistRepository Repository object
     *
     * @var Object
     */
    protected $wishlistRepository;

    /**
     * @var boolean
     */
    protected $suppressFlash = false;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Checkout\Repositories\CartItemRepository $cartItemRepository
     * @param  \Webkul\Product\Repositories\ProductRepository   $productRepository
     * @param  \Webkul\Customer\Repositories\CartItemRepository $wishlistRepository
     * @return void
     */
    public function __construct(
        CartItemRepository $cartItemRepository,
        ProductRepository $productRepository,
        WishlistRepository $wishlistRepository
    )
    {
        $this->middleware('customer')->only(['moveToWishlist']);

        $this->cartItemRepository = $cartItemRepository;

        $this->productRepository = $productRepository;

        $this->wishlistRepository = $wishlistRepository;

        $this->_config = request('_config');
    }

    /**
     * Method to populate the cart page which will be populated before the checkout process.
     *
     * @return Mixed
     */
    public function index()
    {
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
            $product = $this->productRepository->find($id);

            $data = request()->all();
            
            if ($product->type == 'downloadable' && ! isset($data['links'])) {
                session()->flash('warning', trans('shop::app.checkout.cart.integrity.missing_links'));

                return redirect()->route('shop.products.index', $product->url_key);
            } else if ($product->type == 'configurable'
                && (! isset($data['selected_configurable_option']) || ! $data['selected_configurable_option'])) {
                session()->flash('warning', trans('shop::app.checkout.cart.add-config-warning'));

                return redirect()->route('shop.products.index', $product->url_key);
            }

            Event::fire('checkout.cart.add.before', $id);

            $result = Cart::add($id, request()->except('_token'));

            Event::fire('checkout.cart.add.after', $result);

            Cart::collectTotals();

            if ($result) {
                session()->flash('success', trans('shop::app.checkout.cart.item.success'));

                if (auth()->guard('customer')->user()) {
                    $customer = auth()->guard('customer')->user();

                    if (count($customer->wishlist_items)) {
                        foreach ($customer->wishlist_items as $wishlist) {
                            if ($wishlist->product_id == $id) {
                                $this->wishlistRepository->delete($wishlist->id);
                            }
                        }
                    }
                }

                return redirect()->back();
            } else {
                session()->flash('warning', trans('shop::app.checkout.cart.item.error-add'));

                return redirect()->back();
            }

            return redirect()->route($this->_config['redirect']);

        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Removes the item from the cart if it exists
     *
     * @param integer $itemId
     */
    public function remove($itemId)
    {
        Event::fire('checkout.cart.delete.before', $itemId);

        Cart::removeItem($itemId);

        Event::fire('checkout.cart.delete.after', $itemId);

        Cart::collectTotals();

        return redirect()->back();
    }

    /**
     * Updates the quantity of the items present in the cart.
     *
     * @return response
     */
    public function updateBeforeCheckout()
    {
        try {
            $request = request()->except('_token');

            foreach ($request['qty'] as $id => $quantity) {
                if ($quantity <= 0) {
                    session()->flash('warning', trans('shop::app.checkout.cart.quantity.illegal'));

                    return redirect()->back();
                }
            }

            foreach ($request['qty'] as $key => $value) {
                $item = $this->cartItemRepository->findOneByField('id', $key);

                $data['quantity'] = $value;

                Event::fire('checkout.cart.update.before', $item);

                $result = Cart::updateItem($item->product_id, $data, $key);

                if ($result == false) {
                    $this->suppressFlash = true;
                }

                Event::fire('checkout.cart.update.after', $item);

                unset($item);
                unset($data);
            }

            Cart::collectTotals();

            if ($this->suppressFlash) {
                session()->forget('success');
                session()->forget('warning');
                session()->flash('info', trans('shop::app.checkout.cart.partial-cart-update'));
            }
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));
        }

        return redirect()->back();
    }

    public function buyNow($id, $quantity = 1)
    {
        try {
            Event::fire('checkout.cart.add.before', $id);

            $result = Cart::proceedToBuyNow($id, $quantity);

            Event::fire('checkout.cart.add.after', $result);

            Cart::collectTotals();

            if (! $result) {
                return redirect()->back();
            } else {
                return redirect()->route('shop.checkout.onepage.index');
            }
        } catch(\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Function to move a already added product to wishlist
     * will run only on customer authentication.
     *
     * @param instance cartItem $id
     */
    public function moveToWishlist($id)
    {
        $result = Cart::moveToWishlist($id);

        if (! $result) {
            Cart::collectTotals();

            session()->flash('success', trans('shop::app.wishlist.moved'));

            return redirect()->back();
        } else {
            session()->flash('warning', trans('shop::app.wishlist.move-error'));

            return redirect()->back();
        }
    }
}