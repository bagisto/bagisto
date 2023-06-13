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
    public function index()
    {
        $compareItem = $this->compareItemRepository->get();

        return CompareResource::collection($compareItem);
    }

    /**
     * Method for customers to get products in comparison.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function store()
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            $compareProductIds = request()->input('compare_product_ids');

            if ($compareProductIds) {
                foreach ($compareProductIds as $id) {
                    $product[] = $this->productRepository->findOnewhere([
                        'id' => $id,
                    ]);
                }

                return CompareResource::collection($product);
            }

            return false;
        }

        $productId = request()->input('product_id');

        $compareProduct = $this->compareItemRepository->findOneByField([
            'customer_id'  => $customer->id,
            'product_id'   => $productId,
        ]);

        if (! is_null($compareProduct)) {
            return response()->json([
                'message' => trans('shop::app.compare.already-added'),
            ]);
        }

        $this->compareItemRepository->create([
            'customer_id'  => $customer->id,
            'product_id'   => $productId,
        ]);

        return response()->json([
            'message' => trans('shop::app.compare.item-add'),
        ]);
    }

    /**
     * Method for compare items to delete products in comparison.
     */
    public function destroy(): JsonResource
    {
        $productId = request()->input('product_id');

        $compareItem = $this->compareItemRepository->deleteWhere([
            'product_id' => $productId,
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
    public function moveCart(): JsonResource
    {
        try {
            $customer = auth()->guard('customer')->user();

            $productId = request()->input('product_id');

            $data = request()->all();

            if (! $customer) {
                $data['customer_id'] = null;
            } else {
                $data['customer_id'] = $customer->id;
            }

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
                    'message'  => trans('shop::app.compare.item-add-to-cart'),
                ]);
            }
        } catch (\Exception $exception) {
            return new JsonResource([
                'message'   => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Method for compare items move to wishlist products from comparison.
     */
    public function moveToWisthlist(): JsonResource
    {
        try {
            $productId = request()->input('product_id');

            $customer = auth()->guard('customer')->user();

            $product = $this->productRepository->find($productId);

            if (! $product) {
                return response()->json([
                    'message'  => trans('shop::app.compare.product-removed'),
                ]);

            } elseif (
                ! $product->status
                || ! $product->visible_individually
            ) {
                return response()->json([
                    'message'  => trans('shop::app.compare.check-product-visibility'),
                ]);
            }

            $data = [
                'channel_id'  => core()->getCurrentChannel()->id,
                'product_id'  => $productId,
                'customer_id' => $customer->id,
            ];

            $wishlist = $this->wishlistRepository->findOneWhere($data);

            if (
                $product->parent
                && $product->parent->type !== 'configurable'
            ) {
                $product = $this->productRepository->find($product->parent_id);

                $data['product_id'] = $product->id;
            }

            if (! $wishlist) {
                $wishlist = $this->wishlistRepository->create($data);

                $this->compareItemRepository->deleteWhere([
                    'product_id' => $productId,
                ]);

                return response()->json([
                    'data'     => CompareResource::collection($this->compareItemRepository->get()),
                    'message'  => trans('shop::app.compare.wishlist-success'),
                ]);

            }
        } catch (\Exception $exception) {
            return new JsonResource([
                'message'   => $exception->getMessage(),
            ]);
        }
    }
}
