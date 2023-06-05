<?php

namespace Webkul\Shop\Http\Controllers\Customer;

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
     * Address route index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $compareItem = $this->compareItemRepository->get();
        return view('shop::customers.account.compare.index', compact('compareItem'));
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

        if ($compareProduct) {
            return response()->json([
                'message' => trans('shop::app.components.products.already-added'),
            ]);
        }
        
        $this->compareItemRepository->create([
            'customer_id'  => $customerId,
            'product_id'   => $productId,
        ]);

        return response()->json([
            'message' => trans('shop::app.components.products.compare-add'),
        ]);
    }

    /**
     * Method for compare items to delete products in comparison.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function destroyAll() {
        $compareItem = $this->compareItemRepository->deleteWhere([
            'customer_id' => auth()->guard('customer')->user()->id
        ]);

        if ($compareItem) {
            session()->flash('success', trans('shop::app.customers.account.compare.success'));
        } else {
            session()->flash('success', trans('shop::app.customers.account.compare.error'));
        }
        
        return redirect()->back();
    }
}
