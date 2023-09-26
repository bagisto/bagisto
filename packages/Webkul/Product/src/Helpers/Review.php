<?php

namespace Webkul\Product\Helpers;

use Illuminate\Support\Facades\DB;

class Review
{
    /**
     * Returns the product's avg rating
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getReviews($product)
    {
        return $product->reviews()->where('status', 'approved');
    }

    /**
     * Returns the product's avg rating
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return string
     */
    public function getAverageRating($product)
    {
        return number_format(round($product->reviews->where('status', 'approved')->avg('rating'), 2), 1);
    }

    /**
     * Returns the total review of the product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return int
     */
    public function getTotalReviews($product)
    {
        return $product->reviews->where('status', 'approved')->count();
    }

    /**
     * Returns the total rating of the product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return int
     */
    public function getTotalRating($product)
    {
        return $product->reviews->where('status', 'approved')->sum('rating');
    }

    /**
     * Returns reviews with ratings.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getReviewsWithRatings($product)
    {
        return $product->reviews()
            ->where('status', 'approved')
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();
    }

    /**
     * Returns the Percentage rating of the product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getPercentageRating($product)
    {
        $reviews = $this->getReviewsWithRatings($product);

        $totalReviews = $this->getTotalReviews($product);

        for ($i = 5; $i >= 1; $i--) {
            if (! $reviews->isEmpty()) {
                foreach ($reviews as $review) {
                    if ($review->rating == $i) {
                        $percentage[$i] = round(($review->total / $totalReviews) * 100);

                        break;
                    } else {
                        $percentage[$i] = 0;
                    }
                }
            } else {
                $percentage[$i] = 0;
            }
        }

        return $percentage;
    }
}
