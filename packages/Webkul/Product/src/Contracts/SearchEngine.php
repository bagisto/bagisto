<?php

namespace Webkul\Product\Contracts;

interface SearchEngine
{
    /**
     * Search products and return IDs with total count.
     *
     * @return array{ids: array, total: int}
     */
    public function search(array $params, array $options): array;

    /**
     * Get search suggestions for autocomplete.
     */
    public function getSuggestions(?string $query): ?string;

    /**
     * Get maximum product price for filter range.
     */
    public function getMaxPrice(array $params = []): float;

    /**
     * Find a product by URL slug. Returns product ID or null.
     */
    public function findBySlug(string $slug): ?int;
}
