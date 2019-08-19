<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

/**
 * Product Grouped Product Repository
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductGroupedProductRepository extends Repository
{
    public function model()
    {
        return 'Webkul\Product\Contracts\ProductGroupedProduct';
    }

    /**
     * Search simple products for grouped product association
     *
     * @param integer $productId
     * @param string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchSimpleProducts($productId, $term)
    {
        $product = app(ProductRepository::class)->find($productId);

        $groupedProductIds = $product->grouped_products()->pluck('product_id');

        $groupedProductIds = $groupedProductIds->concat([$productId]);

        return app(ProductFlatRepository::class)->scopeQuery(function($query) use($groupedProductIds, $term) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                    ->addSelect('product_flat.*')
                    ->addSelect('product_flat.product_id as id')
                    ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                    ->where('products.type', 'simple')
                    ->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotIn('product_flat.product_id', $groupedProductIds)
                    ->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                    ->orderBy('product_id', 'desc');
        })->get();
    }

    /**
     * @param array   $data
     * @param Product $product
     * @return void
     */
    public function saveGroupedProducts($data, $product)
    {
        $previousGroupedProductIds = $product->grouped_products()->pluck('id');

        if (isset($data['links'])) {
            foreach ($data['links'] as $linkId => $linkInputs) {
                if (str_contains($linkId, 'link_')) {
                    $this->create(array_merge([
                            'product_id' => $product->id,
                        ], $linkInputs));
                } else {
                    if (is_numeric($index = $previousGroupedProductIds->search($linkId)))
                        $previousGroupedProductIds->forget($index);

                    $this->update($linkInputs, $linkId);
                }
            }
        }

        foreach ($previousGroupedProductIds as $previousGroupedProductId) {
            $this->delete($previousGroupedProductId);
        }
    }
}