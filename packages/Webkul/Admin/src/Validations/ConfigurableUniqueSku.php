<?php

namespace Webkul\Admin\Validations;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Webkul\Product\Repositories\ProductRepository;

class ConfigurableUniqueSku implements DataAwareRule, ValidationRule
{
    /**
     * The data under validation.
     */
    protected array $data = [];

    /**
     * Constructor.
     *
     * @param  array  $currentIds
     */
    public function __construct(
        protected $currentIds = null,
    ) {}

    /**
     * Set the data under validation.
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

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
     * Whether the posted variant skus are free: held by no product outside the current variants and not repeated.
     *
     * @return bool
     */
    protected function isSkuExistsInProduct()
    {
        $requestedSkus = collect($this->data['variants'] ?? null)->pluck('sku')->toArray();

        $productRepository = app(ProductRepository::class);

        if (
            $productRepository->whereIn('sku', $requestedSkus)
                ->whereNotIn('id', $this->currentIds)
                ->exists()
        ) {
            return false;
        }

        return ! (count($requestedSkus) !== count(array_unique($requestedSkus)));
    }
}
