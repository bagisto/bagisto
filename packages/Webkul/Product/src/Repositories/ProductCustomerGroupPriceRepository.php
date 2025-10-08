<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Database\QueryException;

class ProductCustomerGroupPriceRepository extends Repository
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductCustomerGroupPrice';
    }

    /**
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function saveCustomerGroupPrices(array $data, $product)
    {
        $previousCustomerGroupPriceIds = $product->customer_group_prices()->pluck('id');

        $processedUniqueIds = [];

        if (isset($data['customer_group_prices'])) {
            foreach ($data['customer_group_prices'] as $customerGroupPriceId => $row) {
                $row['customer_group_id'] = $row['customer_group_id'] == '' ? null : $row['customer_group_id'];

                $row['unique_id'] = implode('|', array_filter([
                    $row['qty'],
                    $product->id,
                    $row['customer_group_id'],
                ]));

                if (in_array($row['unique_id'], $processedUniqueIds)) {
                    throw new \Exception(trans('admin::app.catalog.products.edit.price.group.duplicate-error'), self::DUPLICATE_CUSTOMER_GROUP_PRICE);
                }

                $processedUniqueIds[] = $row['unique_id'];

                if (Str::contains($customerGroupPriceId, 'price_')) {
                    $existingPrice = $this->findOneWhere(['unique_id' => $row['unique_id']]);

                    if ($existingPrice) {
                        throw new \Exception(trans('admin::app.catalog.products.edit.price.group.duplicate-error'));
                    }

                    try {
                        $this->create(array_merge([
                            'product_id' => $product->id,
                        ], $row));
                    } catch (QueryException $e) {
                        if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                            throw new \Exception(trans('admin::app.catalog.products.edit.price.group.duplicate-error'));
                        }

                        throw $e;
                    }
                } else {
                    if (is_numeric($index = $previousCustomerGroupPriceIds->search($customerGroupPriceId))) {
                        $previousCustomerGroupPriceIds->forget($index);
                    }

                    $this->update($row, $customerGroupPriceId);
                }
            }
        }

        foreach ($previousCustomerGroupPriceIds as $customerGroupPriceId) {
            $this->delete($customerGroupPriceId);
        }
    }

    /**
     * Check if product customer group prices already loaded then load from it.
     *
     * @return object
     */
    public function prices($product, $customerGroupId)
    {
        $prices = $product->customer_group_prices->filter(function ($customerGroupPrice) use ($customerGroupId) {
            return $customerGroupPrice->customer_group_id == $customerGroupId
                || is_null($customerGroupPrice->customer_group_id);
        });

        return $prices;
    }
}
