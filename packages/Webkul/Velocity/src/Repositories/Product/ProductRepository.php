<?php

namespace Webkul\Velocity\Repositories\Product;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductFlatRepository;

class ProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     * Returns featured product
     *
     * @return Collection
     */
    public function getFeaturedProducts($count)
    {
        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                return $query->distinct()
                        ->addSelect('product_flat.*')
                        ->where('product_flat.status', 1)
                        ->where('product_flat.visible_individually', 1)
                        ->where('product_flat.featured', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->orderBy('product_id', 'desc');
            })->paginate($count);

        return $results;
    }

}