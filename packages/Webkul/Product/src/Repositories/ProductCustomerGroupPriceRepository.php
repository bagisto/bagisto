<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\Product;

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
     * @param  Product  $product
     * @return void
     */
    public function saveCustomerGroupPrices(array $data, $product)
    {
        $previousCustomerGroupPriceIds = $product->customer_group_prices()->pluck('id');

        $newEntries = [];

        $updateEntries = [];

        if (isset($data['customer_group_prices'])) {
            $uniqueIds = [];

            foreach ($data['customer_group_prices'] as $customerGroupPriceId => $row) {
                $row['customer_group_id'] = $row['customer_group_id'] == '' ? null : $row['customer_group_id'];

                $row['unique_id'] = implode('|', array_filter([
                    $row['qty'],
                    $product->id,
                    $row['customer_group_id'],
                ], fn ($value) => ! is_null($value)));

                if (in_array($row['unique_id'], $uniqueIds)) {
                    throw ValidationException::withMessages([
                        'customer_group_prices' => trans('product::app.catalog.products.edit.price.group.duplicate-group-price-error'),
                    ]);
                }

                $uniqueIds[] = $row['unique_id'];

                if (Str::contains($customerGroupPriceId, 'price_')) {
                    $newEntries[] = array_merge(['product_id' => $product->id], $row);
                } else {
                    if (is_numeric($index = $previousCustomerGroupPriceIds->search($customerGroupPriceId))) {
                        $previousCustomerGroupPriceIds->forget($index);
                    }

                    $updateEntries[$customerGroupPriceId] = $row;
                }
            }
        }

        foreach ($previousCustomerGroupPriceIds as $customerGroupPriceId) {
            $this->delete($customerGroupPriceId);
        }

        foreach ($newEntries as $row) {
            $this->create($row);
        }

        foreach ($updateEntries as $customerGroupPriceId => $row) {
            $this->update($row, $customerGroupPriceId);
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
