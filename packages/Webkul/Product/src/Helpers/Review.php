<?php

namespace Webkul\Product\Helpers;
use DB;

class Review extends AbstractProduct
{
    /**
     * Returns the product's avg rating
     *
     * @param Product $product
     * @return float
     */
    public function getReviews($product)
    {
        return $product->reviews()->where('status', 'approved');
    }

    /**
     * Returns the product's avg rating
     *
     * @param Product $product
     * @return float
     */
    public function getAverageRating($product)
    {
        return number_format(round($product->reviews()->where('status', 'approved')->average('rating'), 2), 1);
    }

    /**
     * Returns the total review of the product
     *
    * @param Product $product
     * @return integer
     */
    public function getTotalReviews($product)
    {
        return $product->reviews()->where('status', 'approved')->count();
    }

     /**
     * Returns the total rating of the product
     *
     * @param Product $product
     * @return integer
     */
    public function getTotalRating($product)
    {
        return $product->reviews()->where('status','approved')->sum('rating');
    }

     /**
     * Returns the Percentage rating of the product
     *
    * @param Product $product
     * @return integer
     */
    public function getPercentageRating($product)
    {
        $reviews = $product->reviews()->where('status','approved')
                    ->select('rating', DB::raw('count(*) as total'))
                    ->groupBy('rating')
                    ->orderBy('rating','desc')
                    ->get();

        for ($i=5; $i >= 1; $i--) {
            if (!$reviews->isEmpty()) {
                foreach ($reviews as $review) {
                    if ($review->rating == $i) {
                        $percentage[$i] = round(($review->total/$this->getTotalReviews($product))*100);
                        break;
                    } else {
                        $percentage[$i]=0;
                    }
                }
            } else {
                $percentage[$i]=0;
            }
        }

        return $percentage;
    }
}