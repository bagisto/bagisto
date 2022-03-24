<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\Event;
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
     * Update review.
     *
     * @param  array  $attributes
     * @param  $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('customer.review.update.before', $id);

        $review = parent::update($attributes, $id);

        Event::dispatch('customer.review.update.after', $review);

        return $review;
    }

    /**
     * Delete a entity in repository by id
     *
     * @param  $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('customer.review.delete.before', $id);

        parent::delete($id);

        Event::dispatch('customer.review.delete.after', $id);
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
