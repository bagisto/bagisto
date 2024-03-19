<?php

namespace Webkul\Admin\Http\Controllers\Customers\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Resources\CompareItemResource;
use Webkul\Customer\Repositories\CompareItemRepository;

class CompareController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CompareItemRepository $compareItemRepository)
    {
    }

    /**
     * Returns the compare items of the customer.
     */
    public function items(int $id): JsonResource
    {
        $compareItems = $this->compareItemRepository
            ->with('product')
            ->where('customer_id', $id)
            ->get();

        return CompareItemResource::collection($compareItems);
    }
}
