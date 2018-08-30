<?php

namespace Webkul\Product\Product;

class Review extends AbstractProduct
{
    /**
     * Returns the product's avg rating
     *
    * @param Product $product
     * @return float
     */
    public function getAverageRating($product)
    {
        return round($product->reviews->average('rating'));
    }

    /**
     * Returns the total review of the product
     *
    * @param Product $product
     * @return integer
     */
    public function getTotalReviews($product)
    {
        return $product->reviews()->count();
    }

    /**
     * Returns the formated created at date
     *
    * @param ProductReview $review
     * @return integer
     */
    public function formatDate($reviewCreatedAt)
    {
        return core()->formatDate($reviewCreatedAt, 'd, M Y');
    }
}