<?php

namespace Webkul\Product\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\ProductReview;

class ProductReviewRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return ProductReview::class;
    }

    /**
     * Retrieve review for customer.
     */
    public function getCustomerReview(): LengthAwarePaginator
    {
        return $this->getModel()
            ->where(['customer_id' => auth()->guard('customer')->user()->id])
            ->with('product')
            ->paginate(5);
    }
}
