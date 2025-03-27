<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;

class ProductBundleOptionProductRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductBundleOptionProduct';
    }

    /**
     * @param  array  $data
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
                    /**
                     * There is a case when the option product is already associated with the options and the
                     * the user is first removing the option product and then again adding the same option product.
                     * In this case, we are getting exception while updating the option product. So, we need to
                     * check if the option product is already associated with the options then we will update the
                     * existing option product otherwise we will create a new option product.
                     */
                    $bundleOptionProduct = $this->firstWhere([
                        'product_id'               => $bundleOptionProductInputs['product_id'],
                        'product_bundle_option_id' => $productBundleOption->id,
                    ]);

                    if ($bundleOptionProduct) {
                        $bundleOptionProduct->update(array_merge([
                            'product_bundle_option_id' => $productBundleOption->id,
                        ], $bundleOptionProductInputs));

                        /**
                         * Remove the option product id from the previous option product ids array so that we
                         * can delete the option product which is not in the updated option products.
                         */
                        if (is_numeric($index = $previousBundleOptionProductIds->search($bundleOptionProduct->id))) {
                            $previousBundleOptionProductIds->forget($index);
                        }
                    } else {
                        $this->create(array_merge([
                            'product_bundle_option_id' => $productBundleOption->id,
                        ], $bundleOptionProductInputs));
                    }
                } else {
                    /**
                     * Remove the option product id from the previous option product ids array so that we
                     * can delete the option product which is not in the updated option products.
                     */
                    if (is_numeric($index = $previousBundleOptionProductIds->search($bundleOptionProductId))) {
                        $previousBundleOptionProductIds->forget($index);
                    }

                    $this->update($bundleOptionProductInputs, $bundleOptionProductId);
                }
            }
        }

        /**
         * Delete the option products which are not in the updated option products.
         */
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
