<?php

namespace Webkul\Ui\DataGrid\Traits;

trait ProvideQueryStringParser
{
    /**
     * Parse the query strings and get it ready to be used.
     *
     * @return array
     */
    private function getQueryStrings()
    {
        $route = request()->route() ? request()->route()->getName() : '';

        $queryString = $this->grabQueryStrings($route == 'admin.datagrid.export' ? request()->get('datagridUrl') : url()->full());

        $parsedQueryStrings = $this->parseQueryStrings($queryString);

        $this->itemsPerPage = isset($parsedQueryStrings['perPage']) ? $parsedQueryStrings['perPage']['eq'] : $this->itemsPerPage;

        unset($parsedQueryStrings['perPage']);

        return $this->updateQueryStrings($parsedQueryStrings);
    }

    /**
     * Grab query strings from url.
     *
     * @param  string  $fullUrl
     * @return string
     */
    private function grabQueryStrings($fullUrl)
    {
        return explode('?', $fullUrl)[1] ?? null;
    }

    /**
     * Parse query strings.
     *
     * @param  string  $queryString
     * @return array
     */
    private function parseQueryStrings($queryString)
    {
        $parsedQueryStrings = [];

        if ($queryString) {
            parse_str(urldecode($queryString), $parsedQueryStrings);

            unset($parsedQueryStrings['page']);
        }

        return $parsedQueryStrings;
    }

    /**
     * Update query strings.
     *
     * @param  array  $parsedQueryStrings
     * @return array
     */
    private function updateQueryStrings($parsedQueryStrings)
    {
        if (isset($parsedQueryStrings['grand_total'])) {
            foreach ($parsedQueryStrings['grand_total'] as $key => $value) {
                $parsedQueryStrings['grand_total'][$key] = str_replace(',', '.', $parsedQueryStrings['grand_total'][$key]);
            }
        }

        foreach ($parsedQueryStrings as $key => $value) {
            if (in_array($key, ['locale'])) {
                if (! is_array($value)) {
                    unset($parsedQueryStrings[$key]);
                }
            } elseif (! is_array($value)) {
                unset($parsedQueryStrings[$key]);
            }
        }

        return $parsedQueryStrings;
    }
}