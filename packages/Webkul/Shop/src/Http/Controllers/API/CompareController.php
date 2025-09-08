<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CompareItemRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\CompareItemResource;

class CompareController extends APIController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CompareItemRepository $compareItemRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Address route index page.
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
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function store()
    {
        $this->validate(request(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $compareProduct = $this->compareItemRepository->findOneByField([
            'customer_id'  => auth()->guard('customer')->user()->id,
            'product_id'   => request()->input('product_id'),
        ]);

        if ($compareProduct) {
            return (new JsonResource([
                'message' => trans('shop::app.compare.already-added'),
            ]))->response()->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        Event::dispatch('customer.compare.create.before');

        $compareProduct = $this->compareItemRepository->create([
            'customer_id' => auth()->guard('customer')->user()->id,
            'product_id'  => request()->input('product_id'),
        ]);

        Event::dispatch('customer.compare.create.after', $compareProduct);

        return new JsonResource([
            'message' => trans('shop::app.compare.item-add-success'),
        ]);
    }

    /**
     * Method to remove the item from compare list.
     */
    public function destroy(): JsonResource
    {
        $productId = request()->input('product_id');

        Event::dispatch('customer.compare.delete.before', $productId);

        $success = $this->compareItemRepository->deleteWhere([
            'product_id'  => $productId,
            'customer_id' => auth()->guard('customer')->user()->id,
        ]);

        Event::dispatch('customer.compare.delete.after', $productId);

        if (! $success) {
            return new JsonResource([
                'message' => trans('shop::app.compare.remove-error'),
            ]);
        }

        if ($customer = auth()->guard('customer')->user()) {
            $productIds = $this->compareItemRepository
                ->findByField('customer_id', $customer->id)
                ->pluck('product_id')
                ->toArray();
        }

        $products = $this->productRepository
            ->whereIn('id', $productIds ?? [])
            ->get();

        return new JsonResource([
            'data'    => CompareItemResource::collection($products),
            'message' => trans('shop::app.compare.remove-success'),
        ]);
    }

    /**
     * Method for remove all items from compare list
     */
    public function destroyAll(): JsonResource
    {
        Event::dispatch('customer.compare.delete-all.before');

        $success = $this->compareItemRepository->deleteWhere([
            'customer_id' => auth()->guard('customer')->user()->id,
        ]);

        Event::dispatch('customer.compare.delete-all.after');

        if (! $success) {
            return new JsonResource([
                'message' => trans('shop::app.compare.remove-error'),
            ]);
        }

        return new JsonResource([
            'message' => trans('shop::app.compare.remove-all-success'),
        ]);
    }
}
