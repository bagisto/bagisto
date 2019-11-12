<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\API\Http\Resources\Customer\Wishlist as WishlistResource;
use Cart;

/**
 * Wishlist controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class WishlistController extends Controller
{
    /**
     * WishlistRepository object
     *
     * @var object
     */
    protected $wishlistRepository;

    /**
     * ProductRepository object
     *
     * @var object
     */
    protected $productRepository;

    /**
     * @param Webkul\Customer\Repositories\WishlistRepository $wishlistRepository
     * @param Webkul\Product\Repositories\ProductRepository   $productRepository
     */
    public function __construct(
        WishlistRepository $wishlistRepository,
        ProductRepository $productRepository
    )
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->middleware('auth:' . $this->guard);

        $this->wishlistRepository = $wishlistRepository;

        $this->productRepository = $productRepository;
    }

    /**
     * Function to add item to the wishlist.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $product = $this->productRepository->findOrFail($id);

        $customer = auth()->guard($this->guard)->user();

        $wishlistItem = $this->wishlistRepository->findOneWhere([
                'channel_id' => core()->getCurrentChannel()->id,
                'product_id' => $id,
                'customer_id' => $customer->id
            ]);

        if (! $wishlistItem) {
            $wishlistItem = $this->wishlistRepository->create([
                    'channel_id' => core()->getCurrentChannel()->id,
                    'product_id' => $id,
                    'customer_id' => $customer->id
                ]);

            return response()->json([
                    'data' => new WishlistResource($wishlistItem),
                    'message' => trans('customer::app.wishlist.success')
                ]);
        } else {
            $this->wishlistRepository->delete($wishlistItem->id);

            return response()->json([
                    'data' => null,
                    'message' => 'Item removed from wishlist successfully.'
                ]);
        }
    }

    /**
     * Move product from wishlist to cart.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function moveToCart($id)
    {
        $wishlistItem = $this->wishlistRepository->findOrFail($id);

        if ($wishlistItem->customer_id != auth()->guard($this->guard)->user()->id)
            return response()->json([
                    'message' => trans('shop::app.security-warning')
                ], 400);

        $result = Cart::moveToCart($wishlistItem);

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                    'data' => 1,
                    'message' => trans('shop::app.wishlist.moved')
                ]);
        } else {
            return response()->json([
                    'data' => -1,
                    'error' => trans('shop::app.wishlist.option-missing')
                ], 400);
        }
    }
}