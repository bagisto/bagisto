<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;

class ProductReviewRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\Product\Contracts\ProductReview::class;
    }

    /**
     * Retrieve review for customerId
     *
     * @param int $customerId
     */
    public function getCustomerReview()
    {
        $customerId = auth()->guard('customer')->user()->id;

        $reviews = $this->model->where(['customer_id' => $customerId])->with('product')->paginate(5);

        return $reviews;
    }
}
