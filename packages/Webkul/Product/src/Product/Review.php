<?php

namespace Webkul\Shop\Product;

class Review extends AbstractProduct
{
    /**
     * Returns the product's avg rating
     *
     * @return float
     */
    public function getAverageRating($product)
    {
        return round($product->reviews->average('rating'));
    }
}