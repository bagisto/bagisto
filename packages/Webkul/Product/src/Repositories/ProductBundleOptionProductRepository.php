<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Str;

class ProductBundleOptionProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductBundleOptionProduct';
    }

    /**
     * @param array  $data
     * @param  \Webkul\Product\Contracts\ProductBundleOption  $productBundleOption
     * @return void
     */
    public function saveBundleOptionProducts($data, $productBundleOption)
    {
        $previousBundleOptionProductIds = $productBundleOption->bundle_option_products()->pluck('id');

        if (isset($data['products'])) {
            $this->setIsDefaultFlag($data);

            foreach ($data['products'] as $bundleOptionProductId => $bundleOptionProductInputs) {
                if (Str::contains($bundleOptionProductId, 'product_')) {
                    $this->create(array_merge([
                        'product_bundle_option_id' => $productBundleOption->id,
                    ], $bundleOptionProductInputs));
                } else {
                    if (is_numeric($index = $previousBundleOptionProductIds->search($bundleOptionProductId))) {
                        $previousBundleOptionProductIds->forget($index);
                    }

                    $this->update($bundleOptionProductInputs, $bundleOptionProductId);
                }
            }
        }

        foreach ($previousBundleOptionProductIds as $previousBundleOptionProductId) {
            $this->delete($previousBundleOptionProductId);
        }
    }

    /**
     * @param  array  $data
     * @return void|null
     */
    public function setIsDefaultFlag(&$data)
    {
        if (! count($data['products'])) {
            return;
        }

        $haveIsDefaultFlag = false;

        foreach ($data['products'] as $key => $product) {
            if (! empty($product['is_default'])) {
                $haveIsDefaultFlag = true;
            } else {
                $data['products'][$key]['is_default'] = 0;
            }
        }

        if (
            ! $haveIsDefaultFlag
            && $data['is_required']
        ) {
            $data['products'][key($data['products'])]['is_default'] = 1;
        }
    }
}