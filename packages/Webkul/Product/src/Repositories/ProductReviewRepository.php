<?php 

namespace Webkul\Product\Repositories;
 
use Webkul\Core\Eloquent\Repository;

/**
 * Product Review Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductReviewRepository extends Repository
{    
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Models\ProductReview';
    }
}