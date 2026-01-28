<?php

namespace Webkul\Admin\Validations;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Webkul\Product\Repositories\ProductRepository;

class ConfigurableUniqueSku implements ValidationRule
{
    /**
     * Constructor.
     *
     * @param  array  $currentIds
     */
    public function __construct(
        protected $currentIds = null,
    ) {}

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->isSkuExistsInProduct()) {
            $fail('admin::app.catalog.products.index.already-taken')->translate(['name' => $attribute]);
        }
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
