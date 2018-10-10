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
        $reviews['five'] = $product->reviews()->where('rating', 5)->where('status','approved')->count();

        $reviews['four'] = $product->reviews()->where('rating', 4)->where('status','approved')->count();

        $reviews['three'] = $product->reviews()->where('rating', 3)->where('status','approved')->count();

        $reviews['two'] = $product->reviews()->where('rating', 2)->where('status','approved')->count();

        $reviews['one'] = $product->reviews()->where('rating', 1)->where('status','approved')->count();

        foreach($reviews as $key=>$review){

            if($this->getTotalReviews($product) == 0){
                $percentage[$key]=0;
            }else{
                $percentage[$key] = round(($review/$this->getTotalReviews($product))*100);
            }
        }

        return $percentage;
    }

    /**
    * Returns the product accroding to paginate
    *
    * @param Product $product
    * @return integer
    */

    public function loadMore($product)
    {
        $link = $_SERVER['PHP_SELF'];
        $link_array = explode('/',$link);
        $last=end($link_array);
        $itemPerPage = 1*5;

        return $product->reviews()->where('status','approved')->paginate($itemPerPage);
    }
}