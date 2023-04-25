<?php

namespace Webkul\Admin\Validations;

use Illuminate\Contracts\Validation\Rule;
use Webkul\Product\Repositories\ProductRepository;

class ConfigurableUniqueSku implements Rule
{
    /**
     * Constructor.
     *
     * @param  array  $currentIds
     */
    public function __construct(
        protected $currentIds = null,
    ) {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->isSkuExistsInProduct();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('admin::app.response.already-taken', ['name' => ':attribute']);
    }

    /**
     * Is SKU is exists in product.
     *
     * @return bool
     */
    protected function isSkuExistsInProduct()
    {
        $requestedSkus = collect(request()->get('variants'))->pluck('sku')->toArray();

        $productRepository = app(ProductRepository::class);

        /**
         * First we will check sku in all the products except the
         * current variant ids.
         */
        if (
            $productRepository->whereIn('sku', $requestedSkus)
                ->whereNotIn('id', $this->currentIds)
                ->exists()
        ) {
            return false;
        }

        /**
         * Once, we don't found any sku in all the products then
         * we will check uniqueness in the current requested variant's skus.
         */
        return ! (count($requestedSkus) !== count(array_unique($requestedSkus)));
    }
}
