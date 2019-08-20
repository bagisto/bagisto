<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * ProductBundleOption Repository
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductBundleOptionRepository extends Repository
{
    public function model()
    {
        return 'Webkul\Product\Contracts\ProductBundleOption';
    }
}