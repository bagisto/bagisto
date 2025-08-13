<?php

namespace Webkul\Sales\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Order Address Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 BannerKW Pvt Ltd (http://www.webkul.com)
 */
class OrderAddressRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Sales\Contracts\OrderAddress';
    }
}
