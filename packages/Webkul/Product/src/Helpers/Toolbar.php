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
                'title'    => trans('shop::app.products.from-a-z'),
                'value'    => 'name-asc',
                'sort'     => 'name',
                'order'    => 'asc',
                'position' => 1,
            ],
            [
                'title'    => trans('shop::app.products.from-z-a'),
                'value'    => 'name-desc',
                'sort'     => 'name',
                'order'    => 'desc',
                'position' => 2,
            ],
            [
                'title'    => trans('shop::app.products.latest-first'),
                'value'    => 'created_at-desc',
                'sort'     => 'created_at',
                'order'    => 'desc',
                'position' => 3,
            ],
            [
                'title'    => trans('shop::app.products.oldest-first'),
                'value'    => 'created_at-asc',
                'sort'     => 'created_at',
                'order'    => 'asc',
                'position' => 4,
            ],
            [
                'title'    => trans('shop::app.products.cheapest-first'),
                'value'    => 'price-asc',
                'sort'     => 'price',
                'order'    => 'asc',
                'position' => 5,
            ],
            [
                'title'    => trans('shop::app.products.expensive-first'),
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
     *
     * @return integer
     */
    public function getDefaultLimit(): int
    {
        return 12;
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
     * Returns the mode url.
     *
     * @param  string  $mode
     * @return string
     */
    public function getModeUrl($mode)
    {
        return $this->fullUrlWithQuery([
            'mode' => $mode,
        ]);
    }

    /**
     * Checks if mode is active.
     *
     * @param  string  $key
     * @return bool
     */
    public function isModeActive($key)
    {
        $params = request()->input();

        $defaultMode = core()->getConfigData('catalog.products.storefront.mode') ?: 'grid';

        if (
            request()->input() == null
            && $key == $defaultMode
        ) {
            return true;
        } elseif (
            isset($params['mode'])
            && $key == $params['mode']
        ) {
            return true;
        }

        return false;
    }

    /**
     * Returns the current mode.
     *
     * @return string
     */
    public function getCurrentMode()
    {
        $params = request()->input();

        if (isset($params['mode'])) {
            return $params['mode'];
        }

        return core()->getConfigData('catalog.products.storefront.mode') ?: 'grid';
    }

    /**
     * Returns the view option if mode is set by param then it will overwrite default one and return new mode.
     *
     * @return string
     */
    public function getViewOption()
    {
        /* checking default option first */
        $viewOption = core()->getConfigData('catalog.products.storefront.mode');

        /* checking mode param if exist then overwrite the default option */
        if ($this->isModeActive('grid')) {
            $viewOption = 'grid';
        }

        /* checking mode param if exist then overwrite the default option */
        if ($this->isModeActive('list')) {
            $viewOption = 'list';
        }

        /* if still default config is not set from the admin then in last needed hardcoded value */
        return $viewOption ?? 'grid';
    }

    /**
     * Returns the query string. As request built in method does not able to handle the
     * multiple question marks, this method will check the query string and append the query string.
     *
     * @param  array  $additionalQuery
     * @return string
     */
    public function fullUrlWithQuery($additionalQuery)
    {
        $requestQuery = array_merge(request()->query(), $additionalQuery);

        $queryString = http_build_query($requestQuery);

        return url()->current() . '?' . $queryString;
    }
}
