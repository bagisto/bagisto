<?php

namespace Webkul\Velocity\Repositories;

use Prettus\Repository\Traits\CacheableRepository;
use Webkul\Core\Eloquent\Repository;

/**
 * Review Repository
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewRepository extends Repository
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Product\Contracts\ProductReview';
    }
}
