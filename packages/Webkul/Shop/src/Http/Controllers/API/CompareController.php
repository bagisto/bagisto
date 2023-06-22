<?php

namespace Webkul\Shop\Http\Controllers\API;

use Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Customer\Repositories\CompareItemRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\CompareItemResource;

class CompareController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CompareItemRepository  $compareItemRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(
        protected CompareItemRepository $compareItemRepository,
        protected ProductRepository $productRepository
    ) {
    }

    /**
     * Address route index page.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(): JsonResource
    {
        $productIds = request()->input('product_ids') ?? [];

        /**
         * This will handle for customers.
         */
        if ($customer = auth()->guard('customer')->user()) {
            $productIds = $this->compareItemRepository
                ->findByField('customer_id', $customer->id)
                ->pluck('product_id')
                ->toArray();
        }

        $products = $this->productRepository
            ->whereIn('id', $productIds)
            ->get();

        return CompareItemResource::collection($products);
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
     * 
     * @return \Illuminate\Http\Resources\Json\JsonResource
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
}
