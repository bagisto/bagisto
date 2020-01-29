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

    /**
     * Returns newly added product
     *
     * @return Collection
     */
    public function getNewProducts($count)
    {
        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) {
                $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                $locale = request()->get('locale') ?: app()->getLocale();

                return $query->distinct()
                        ->addSelect('product_flat.*')
                        ->where('product_flat.status', 1)
                        ->where('product_flat.visible_individually', 1)
                        ->where('product_flat.new', 1)
                        ->where('product_flat.channel', $channel)
                        ->where('product_flat.locale', $locale)
                        ->orderBy('product_id', 'desc');
            })->paginate($count);

        return $results;
    }


    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchProductsFromCategory($params)
    {
        $term = $params['term'];
        $categoryId = $params['category'];

        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) use($term, $categoryId) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $query = $query->distinct()
                    ->addSelect('product_flat.*')
                    ->where('product_flat.status', 1)
                    ->where('product_flat.visible_individually', 1)
                    ->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotNull('product_flat.url_key')
                    ->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                    ->orderBy('product_id', 'desc');

            if ($categoryId && $categoryId !== "") {
                $query = $query
                        ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                        ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                        ->where('product_categories.category_id', $categoryId);
            }

            return $query;
        })->paginate(16);

        return $results;
    }
}