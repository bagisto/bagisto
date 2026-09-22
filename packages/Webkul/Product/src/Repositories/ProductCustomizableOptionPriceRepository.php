<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\ProductCustomizableOption;
use Webkul\Product\Contracts\ProductCustomizableOptionPrice;

class ProductCustomizableOptionPriceRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductCustomizableOptionPrice::class;
    }

    /**
     * Save customizable option prices, updating the ones the option already has and creating any other,
     * so an id from the request never reaches another option's price.
     *
     * @param  array  $data
     * @param  ProductCustomizableOption  $productCustomizableOption
     * @return void
     */
    public function saveCustomizableOptionPrices($data, $productCustomizableOption)
    {
        $previousCustomizableOptionPriceIds = $productCustomizableOption->customizable_option_prices()->pluck('id');

        if (isset($data['prices'])) {
            foreach ($data['prices'] as $customizableOptionPriceId => $customizableOptionPriceInputs) {
                $index = $previousCustomizableOptionPriceIds->search($customizableOptionPriceId);

                if ($index === false) {
                    $this->create(array_merge([
                        'product_customizable_option_id' => $productCustomizableOption->id,
                    ], $customizableOptionPriceInputs));
                } else {
                    $previousCustomizableOptionPriceIds->forget($index);

                    $this->update($customizableOptionPriceInputs, $customizableOptionPriceId);
                }
            }
        }

        foreach ($previousCustomizableOptionPriceIds as $previousCustomizableOptionPriceId) {
            $this->delete($previousCustomizableOptionPriceId);
        }
    }
}
