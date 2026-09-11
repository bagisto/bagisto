<?php

namespace Webkul\FPC\Listeners;

use Webkul\FPC\Concerns\ForgetsPages;
use Webkul\Product\Contracts\ProductReview;
use Webkul\Product\Repositories\ProductReviewRepository;

class Review
{
    use ForgetsPages;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected ProductReviewRepository $productReviewRepository) {}

    /**
     * After review is updated.
     *
     * @param  ProductReview  $review
     * @return void
     */
    public function afterUpdate($review)
    {
        $this->forgetPages([$this->productPath($review)]);
    }

    /**
     * Before review is deleted.
     *
     * @param  int  $reviewId
     * @return void
     */
    public function beforeDelete($reviewId)
    {
        $review = $this->productReviewRepository->find($reviewId);

        if (! $review) {
            return;
        }

        $this->forgetPages([$this->productPath($review)]);
    }

    /**
     * The address of the product page a review is shown on.
     *
     * @param  ProductReview  $review
     */
    protected function productPath($review): ?string
    {
        return $review->product?->url_key
            ? '/'.$review->product->url_key
            : null;
    }
}
