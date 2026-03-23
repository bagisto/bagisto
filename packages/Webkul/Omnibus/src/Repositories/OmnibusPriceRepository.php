<?php

namespace Webkul\Omnibus\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Omnibus\Contracts\OmnibusPrice;

class OmnibusPriceRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Omnibus\Contracts\OmnibusPrice';
    }

    /**
     * Get latest price history for a product.
     *
     * @return OmnibusPrice|null
     */
    public function getLatestByProductId(int $productId)
    {
        return $this->model->where('product_id', $productId)->latest()->first();
    }
}
