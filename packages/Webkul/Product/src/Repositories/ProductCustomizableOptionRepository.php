<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Contracts\ProductCustomizableOption;

class ProductCustomizableOptionRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductCustomizableOptionPriceRepository $productCustomizableOptionPriceRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductCustomizableOption::class;
    }

    /**
     * Save customizable options, updating the ones the product already has and creating any other,
     * so an id from the request never reaches another product's option.
     *
     * @param  array  $data
     * @param  Product  $product
     * @return void
     */
    public function saveCustomizableOptions($data, $product)
    {
        $previousCustomizableOptionIds = $product->customizable_options()->pluck('id');

        if (isset($data['customizable_options'])) {
            foreach ($data['customizable_options'] as $customizableOptionId => $customizableOptionInputs) {
                $index = $previousCustomizableOptionIds->search($customizableOptionId);

                if ($index === false) {
                    $productCustomizableOption = $this->create(array_merge([
                        'product_id' => $product->id,
                    ], $customizableOptionInputs));
                } else {
                    $previousCustomizableOptionIds->forget($index);

                    $productCustomizableOption = $this->update($customizableOptionInputs, $customizableOptionId);
                }

                $this->productCustomizableOptionPriceRepository->saveCustomizableOptionPrices($customizableOptionInputs, $productCustomizableOption);
            }
        }

        foreach ($previousCustomizableOptionIds as $previousCustomizableOptionId) {
            $this->delete($previousCustomizableOptionId);
        }
    }
}
