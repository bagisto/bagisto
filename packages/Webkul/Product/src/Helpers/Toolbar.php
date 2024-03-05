<?php

namespace Webkul\Product\Helpers;

use Illuminate\Support\Collection;

class Toolbar
{
    /**
     * Returns available sort orders.
     */
    public function getAvailableOrders(): Collection
    {
        return collect([
            [
                'title'    => trans('product::app.sort-by.options.from-a-z'),
                'value'    => 'name-asc',
                'sort'     => 'name',
                'order'    => 'asc',
                'position' => 1,
            ],
            [
                'title'    => trans('product::app.sort-by.options.from-z-a'),
                'value'    => 'name-desc',
                'sort'     => 'name',
                'order'    => 'desc',
                'position' => 2,
            ],
            [
                'title'    => trans('product::app.sort-by.options.latest-first'),
                'value'    => 'created_at-desc',
                'sort'     => 'created_at',
                'order'    => 'desc',
                'position' => 3,
            ],
            [
                'title'    => trans('product::app.sort-by.options.oldest-first'),
                'value'    => 'created_at-asc',
                'sort'     => 'created_at',
                'order'    => 'asc',
                'position' => 4,
            ],
            [
                'title'    => trans('product::app.sort-by.options.cheapest-first'),
                'value'    => 'price-asc',
                'sort'     => 'price',
                'order'    => 'asc',
                'position' => 5,
            ],
            [
                'title'    => trans('product::app.sort-by.options.expensive-first'),
                'value'    => 'price-desc',
                'sort'     => 'price',
                'order'    => 'desc',
                'position' => 6,
            ],
        ]);
    }

    /**
     * Get default order. This is a crucial part of our system configuration.
     * It should either be available or fail. There should be no further proceeding.
     */
    public function getDefaultOrder(): array
    {
        return $this->getAvailableOrders()
            ->where('value', core()->getConfigData('catalog.products.storefront.sort_by') ?? 'price-desc')
            ->firstOrFail();
    }

    /**
     * Get order.
     */
    public function getOrder(array $params = []): array
    {
        if (! isset($params['sort'])) {
            return $this->getDefaultOrder();
        }

        $order = $this->getAvailableOrders()
            ->where('value', $params['sort'])
            ->first();

        return $order ?: $this->getDefaultOrder();
    }

    /**
     * Returns available limits.
     */
    public function getAvailableLimits(): Collection
    {
        if ($productsPerPage = core()->getConfigData('catalog.products.storefront.products_per_page')) {
            $pages = explode(',', $productsPerPage);

            return collect($pages);
        }

        return collect([12, 24, 36, 48]);
    }

    /**
     * Returns default limit. By default it will be 12. Leaved a
     * space for the admin configuration and customization.
     */
    public function getDefaultLimit(): int
    {
        return $this->getAvailableLimits()->first();
    }

    /**
     * Get limit.
     */
    public function getLimit(array $params): int
    {
        /**
         * Set a default value for the 'limit' parameter,
         * in case it is not provided or is not a valid integer.
         */
        $limit = (int) ($params['limit'] ?? $this->getDefaultLimit());

        /**
         * If the 'limit' parameter is present but value not present
         * in available limits, use the default value instead.
         */
        return in_array($limit, $this->getAvailableLimits()->toArray())
            ? $limit
            : $this->getDefaultLimit();
    }

    /**
     * Returns available modes.
     */
    public function getAvailableModes(): Collection
    {
        return collect(['grid', 'list']);
    }

    /**
     * Returns default mode.
     *
     * @return int
     */
    public function getDefaultMode(): string
    {
        return core()->getConfigData('catalog.products.storefront.mode') ?? 'grid';
    }

    /**
     * Get mode.
     */
    public function getMode(array $params): string
    {
        /**
         * Set a default value for the 'mode' parameter,
         * in case it is not provided.
         */
        $mode = $params['mode'] ?? $this->getDefaultMode();

        /**
         * If the 'mode' parameter is present but value not present
         * in available modes, use the default mode instead.
         */
        return in_array($mode, $this->getAvailableModes()->toArray())
            ? $mode
            : $this->getDefaultMode();
    }
}
