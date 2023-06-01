<?php

namespace Webkul\Shop\Http\Controllers\Customer\Account;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CompareItemRepository;

class CompareController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param Webkul\Customer\Repositories\CompareItemRepository  $compareItemsRepository
     *
     * @return void
     */
    public function __construct(protected CompareItemRepository $compareItemRepository)
    {
    }

    /**
     * Method for customers to get products in comparison.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function store($productId)
    {
        $customerId = auth()->guard('customer')->user()->id;

        $compareProduct = $this->compareItemRepository->findOneByField([
            'customer_id'  => $customerId,
            'product_id'   => $productId,
        ]);

        if (! $compareProduct) {
            $this->compareItemRepository->create([
                'customer_id'  => $customerId,
                'product_id'   => $productId,
            ]);

            return response()->json([
                'message' => trans('shop::app.component.products.compare-add'),
            ]);
        } else {
            return response()->json([
                'message' => trans('shop::app.component.products.already-added'),
            ]);
        }
    }
}
