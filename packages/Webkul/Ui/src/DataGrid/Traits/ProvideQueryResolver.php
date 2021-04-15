<?php

namespace Webkul\Ui\DataGrid\Traits;

trait ProvideQueryResolver
{
    /**
     * Main resolve method.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  string                          $columnName
     * @param  string                          $condition
     * @param  string                          $filter_value
     * @param  string                          $clause
     * @param  string                          $method
     * @return void
     */
    private function resolve($collection, $columnName, $condition, $filter_value, $clause = 'where', $method = 'resolveQuery')
    {
        if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
            $this->$method($collection, $this->filterMap[$columnName], $condition, $filter_value, $clause);
        } else if ($this->enableFilterMap && ! isset($this->filterMap[$columnName])) {
            $this->$method($collection, $columnName, $condition, $filter_value, $clause);
        } else {
            $this->$method($collection, $columnName, $condition, $filter_value, $clause);
        }
    }

    /**
     * Resolve query.
     *
     * @param  object        $query
     * @param  string        $columnName
     * @param  string        $condition
     * @param  string        $filter_value
     * @param  null|boolean  $nullCheck
     * @return void
     */
    private function resolveQuery($query, $columnName, $condition, $filter_value, $clause = 'where')
    {
        $query->$clause(
            $columnName,
            $this->operators[$condition],
            $filter_value
        );
    }

    /**
     * Resolve boolean query.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  string                          $columnName
     * @param  string                          $condition
     * @param  string                          $filter_value
     * @return void
     */
    private function resolveBooleanQuery($collection, $columnName, $condition, $filter_value)
    {
        if ($this->operators[$condition] == '=') {
            if ($filter_value == 1) {
                $this->resolveFilterQuery($collection, $columnName, $condition, $filter_value, false);
            } else {
                $this->resolveFilterQuery($collection, $columnName, $condition, $filter_value, true);
            }
        } else if ($this->operators[$condition] == '<>') {
            if ($filter_value == 1) {
                $this->resolveFilterQuery($collection, $columnName, $condition, $filter_value, true);
            } else {
                $this->resolveFilterQuery($collection, $columnName, $condition, $filter_value, false);
            }
        } else {
            $this->resolveFilterQuery($collection, $columnName, $condition, $filter_value);
        }
    }

    /**
     * Resolve filter query.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  string                          $columnName
     * @param  string                          $condition
     * @param  string                          $filter_value
     * @param  null|boolean                    $nullCheck
     * @return void
     */
    private function resolveFilterQuery($collection, $columnName, $condition, $filter_value, $nullCheck = null)
    {
        $clause = is_null($nullCheck) ? null : ( $nullCheck ? 'orWhereNull' : 'orWhereNotNull' );

        $collection->where(function ($query) use ($columnName, $condition, $filter_value, $clause) {
            $this->resolveQuery($query, $columnName, $condition, $filter_value);

            if (! is_null($clause)) {
                $query->$clause(($this->filterMap[$columnName]));
            }
        });
    }
}