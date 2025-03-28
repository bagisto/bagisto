<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;

class ProductGroupedProductRepository extends Repository
{
    /**
     * Specify model.
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductGroupedProduct';
    }

    /**
     * Save grouped products.
     *
     * @param  array  $data
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function saveGroupedProducts($data, $product)
    {
        $previousGroupedProductIds = $product->grouped_products()->pluck('id');

        if (isset($data['links'])) {
            foreach ($data['links'] as $linkId => $linkInputs) {
                if (Str::contains($linkId, 'link_')) {
                    /**
                     * There is a case when the grouped product is already associated with the product and the
                     * the user is first removing the product and then again adding the same grouped product. In
                     * this case, we are getting exception while updating the grouped product. So, we need to
                     * check if the grouped product is already associated with the product then we will update the
                     * existing grouped product otherwise we will create a new grouped product.
                     */
                    $groupedProduct = $this->firstWhere([
                        'product_id'            => $product->id,
                        'associated_product_id' => $linkInputs['associated_product_id'],
                    ]);

                    if ($groupedProduct) {
                        $groupedProduct->update(array_merge([
                            'product_id' => $product->id,
                        ], $linkInputs));

                        /**
                         * Remove the grouped product id from the previous grouped product ids array so that we
                         * can delete the grouped product which is not in the updated grouped products.
                         */
                        if (is_numeric($index = $previousGroupedProductIds->search($groupedProduct->id))) {
                            $previousGroupedProductIds->forget($index);
                        }
                    } else {
                        $this->create(array_merge([
                            'product_id' => $product->id,
                        ], $linkInputs));
                    }
                } else {
                    /**
                     * Remove the grouped product id from the previous grouped product ids array so that we
                     * can delete the grouped product which is not in the updated grouped products.
                     */
                    if (is_numeric($index = $previousGroupedProductIds->search($linkId))) {
                        $previousGroupedProductIds->forget($index);
                    }

                    $this->update($linkInputs, $linkId);
                }
            }
        }

        /**
         * Delete all the previous grouped products which are not in the updated grouped products.
         */
        foreach ($previousGroupedProductIds as $previousGroupedProductId) {
            $this->delete($previousGroupedProductId);
        }
    }
}
