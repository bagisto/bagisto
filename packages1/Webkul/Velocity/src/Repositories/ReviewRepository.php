<?php

namespace Webkul\Velocity\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Review Reposotory
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return 'Webkul\Product\Contracts\ProductReview';
    }


    function getAll() 
    {
        $reviews = $this->model->get();
        
        return $reviews;
    }
}