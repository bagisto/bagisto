<?php

namespace Webkul\Product\Services\Search\Engines;

use Webkul\Attribute\Enums\AttributeTypeEnum;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Contracts\SearchEngine;
use Webkul\Product\Repositories\ProductRepository;

class DatabaseEngine implements SearchEngine
{
    /**
     * Create a new instance.
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected AttributeRepository $attributeRepository,
        protected ProductRepository $productRepository,
    ) {}

    /**
     * Database engine does not support search by IDs — returns empty.
     *
     * Product listing is handled by ProductRepository::searchFromDatabase().
     */
    public function search(array $params, array $options): array
    {
        return [
            'ids' => [],
            'total' => 0,
        ];
    }

    /**
     * Database engine does not support search suggestions.
     */
    public function getSuggestions(?string $query): ?string
    {
        return null;
    }

    /**
     * Get maximum product price from database.
     */
    public function getMaxPrice(array $params = []): float
    {
        $attributeCode = $params['attribute_code'] ?? 'price';

        if ($attributeCode === 'price') {
            $customerGroup = $this->customerRepository->getCurrentGroup();

            $query = $this->productRepository
                ->leftJoin('product_price_indices', 'products.id', 'product_price_indices.product_id')
                ->leftJoin('product_categories', 'products.id', 'product_categories.product_id')
                ->where('product_price_indices.customer_group_id', $customerGroup->id);

            if (! empty($params['category_id'])) {
                $query->where('product_categories.category_id', $params['category_id']);
            }

            return $query->max('min_price') ?? 0;
        }

        $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

        if (
            ! $attribute
            || $attribute->type !== AttributeTypeEnum::PRICE->value
        ) {
            return 0;
        }

        $query = $this->productRepository
            ->leftJoin('product_attribute_values as attr_pav', function ($join) use ($attribute) {
                $join->on('products.id', '=', 'attr_pav.product_id')
                    ->where('attr_pav.attribute_id', $attribute->id);
            });

        if (! empty($params['category_id'])) {
            $query->leftJoin('product_categories', 'products.id', 'product_categories.product_id')
                ->where('product_categories.category_id', $params['category_id']);
        }

        return $query->max('attr_pav.float_value') ?? 0;
    }

    /**
     * Database engine does not support slug lookup — returns null.
     *
     * ProductRepository falls back to findByAttributeCode().
     */
    public function findBySlug(string $slug): ?int
    {
        return null;
    }
}
