<?php

namespace Webkul\Shop\Http\Controllers\API;

use Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Customer\Repositories\CompareItemRepository;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\CompareResource;

class CompareController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CompareItemRepository $compareItemRepository,
        protected ProductRepository $productRepository,
        protected WishlistRepository $wishlistRepository
    ) {
    }

    /**
     * Address route index page.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(): JsonResource
    {
        /**
         * This will handle for customers.
         */
        if (auth()->guard('customer')->check()) {
            $compareItem = $this->compareItemRepository->get();

            return CompareResource::collection($compareItem);
        }

        /**
         * This will handle for guest users.
         */
        if ($productIds = request()->input('product_ids')) {
            $products = $this->productRepository->whereIn('id', $productIds)->get();

            return CompareResource::collection($products);
        }

        return new JsonResource([
            'message' => trans('shop::app.compare.products-not-available'),
        ]);
    }

    /**
     * Method for customers to get products in comparison.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function store(): JsonResource
    {
        $compareProduct = $this->compareItemRepository->findOneByField([
            'customer_id'  => auth()->guard('customer')->user()->id,
            'product_id'   => request()->input('product_id'),
        ]);

        if ($compareProduct) {
            return new JsonResource([
                'message' => trans('shop::app.compare.already-added'),
            ]);
        }

        $this->compareItemRepository->create([
            'customer_id'  => auth()->guard('customer')->user()->id,
            'product_id'   => request()->input('product_id'),
        ]);

        return new JsonResource([
            'message' => trans('shop::app.compare.item-add'),
        ]);
    }

    /**
     * Method for compare items to delete products in comparison.
     */
    public function destroy(): JsonResource
    {
        $compareItem = $this->compareItemRepository->deleteWhere([
            'product_id' => request()->input('product_id'),
        ]);

        $compareData = $this->compareItemRepository->get();

        if ($compareItem) {
            return new JsonResource([
                'data'     => CompareResource::collection($compareData),
                'message'  => trans('shop::app.compare.success'),
            ]);
        }

        return new JsonResource([
            'message'  => trans('shop::app.compare.error'),
        ]);
    }

    /**
     * Method for compare items move to cart products from comparison.
     */
    public function moveToCart(): JsonResource
    {
        try {
            $customer = auth()->guard('customer')->user();

            $productId = request()->input('product_id');

            $data = request()->all();

            $data['customer_id'] = $customer ? $customer->id : null;

            $cart = Cart::addProduct($productId, $data);

            if (
                is_array($cart)
                && isset($cart['warning'])
            ) {
                return new JsonResource([
                    'message' => $cart['warning'],
                ]);
            }

            if ($cart) {
                if ($customer) {
                    $this->compareItemRepository->deleteWhere([
                        'product_id'  => $productId,
                        'customer_id' => $customer->id,
                    ]);
                }

                return new JsonResource([
                    'message' => trans('shop::app.compare.item-add-to-cart'),
                ]);
            }
        } catch (\Exception $exception) {
            return new JsonResource([
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Method for compare items move to wishlist products from comparison.
     */
    public function moveToWishlist(): JsonResource
    {
        try {
            $product = $this->productRepository->find(request()->input('product_id'));

            if (! $product) {
                return new JsonResource([
                    'message'  => trans('shop::app.compare.product-removed'),
                ]);

            } elseif (
                ! $product->status
                || ! $product->visible_individually
            ) {
                return new JsonResource([
                    'message'  => trans('shop::app.compare.check-product-visibility'),
                ]);
            }

            $data = [
                'channel_id'  => core()->getCurrentChannel()->id,
                'product_id'  => $product->id,
                'customer_id' => auth()->guard('customer')->user()->id,
            ];

            if (
                $product->parent
                && $product->parent->type !== 'configurable'
            ) {
                $product = $this->productRepository->find($product->parent_id);

                $data['product_id'] = $product->id;
            }

            if (! $this->wishlistRepository->findOneWhere($data)) {
                $this->wishlistRepository->create($data);

                $compareItem = $this->compareItemRepository->deleteWhere([
                    'product_id' => $product->id,
                ]);

                return new JsonResource([
                    'data'     => CompareResource::collection($this->compareItemRepository->get()),
                    'message'  => trans('shop::app.compare.wishlist-success'),
                ]);
            }

            return new JsonResource([
                'data'     => CompareResource::collection($this->compareItemRepository->get()),
                'message'  => trans('shop::app.compare.alredy-in-wishlist'),
            ]);
        } catch (\Exception $exception) {
            return new JsonResource([
                'message'   => $exception->getMessage(),
            ]);
        }
    }
}
