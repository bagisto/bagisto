<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * ProductBundleOptionProduct Repository
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductBundleOptionProductRepository extends Repository
{
    public function model()
    {
        return 'Webkul\Product\Contracts\ProductBundleOptionProduct';
    }

    /**
     * @param array   $data
     * @param ProductBundleOption $productBundleOption
     * @return void
     */
    public function saveBundleOptonProducts($data, $productBundleOption)
    {
        $previousBundleOptionProductIds = $productBundleOption->bundle_option_products()->pluck('id');

        if (isset($data['products'])) {
            foreach ($data['products'] as $bundleOptionProductId => $bundleOptionProductInputs) {
                if (str_contains($bundleOptionProductId, 'product_')) {
                    $this->create(array_merge([
                            'product_bundle_option_id' => $productBundleOption->id,
                        ], $bundleOptionProductInputs));
                } else {
                    if (is_numeric($index = $previousBundleOptionProductIds->search($bundleOptionProductId)))
                        $previousBundleOptionProductIds->forget($index);

                    $this->update($bundleOptionProductInputs, $bundleOptionProductId);
                }
            }
        }

        foreach ($previousBundleOptionProductIds as $previousBundleOptionProductId) {
            $this->delete($previousBundleOptionProductId);
        }
    }
}