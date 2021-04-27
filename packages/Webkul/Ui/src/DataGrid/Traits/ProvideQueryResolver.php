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
     * @param  string                          $filterValue
     * @param  string                          $clause
     * @param  string                          $method
     * @return void
     */
    private function resolve($collection, $columnName, $condition, $filterValue, $clause = 'where', $method = 'resolveQuery')
    {
        if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
            $this->$method($collection, $this->filterMap[$columnName], $condition, $filterValue, $clause);
        } else if ($this->enableFilterMap && ! isset($this->filterMap[$columnName])) {
            $this->$method($collection, $columnName, $condition, $filterValue, $clause);
        } else {
            $this->$method($collection, $columnName, $condition, $filterValue, $clause);
        }
    }

    /**
     * Resolve query.
     *
     * @param  object        $query
     * @param  string        $columnName
     * @param  string        $condition
     * @param  string        $filterValue
     * @param  null|boolean  $nullCheck
     * @return void
     */
    private function resolveQuery($query, $columnName, $condition, $filterValue, $clause = 'where')
    {
        $query->$clause(
            $columnName,
            $this->operators[$condition],
            $filterValue
        );
    }

    /**
     * Resolve boolean query.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  string                          $columnName
     * @param  string                          $condition
     * @param  string                          $filterValue
     * @return void
     */
    private function resolveBooleanQuery($collection, $columnName, $condition, $filterValue)
    {
        if ($this->operators[$condition] == '=') {
            $this->checkFilterValueCondition($collection, $columnName, $condition, $filterValue);
        } else if ($this->operators[$condition] == '<>') {
            $this->checkFilterValueCondition($collection, $columnName, $condition, $filterValue, true);
        } else {
            $this->resolveFilterQuery($collection, $columnName, $condition, $filterValue);
        }
    }

    /**
     * Resolve filter query.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  string                          $columnName
     * @param  string                          $condition
     * @param  string                          $filterValue
     * @param  null|boolean                    $nullCheck
     * @return void
     */
    private function resolveFilterQuery($collection, $columnName, $condition, $filterValue, $nullCheck = null)
    {
        $clause = is_null($nullCheck) ? null : ( $nullCheck ? 'orWhereNull' : 'whereNotNull' );

        $collection->where(function ($query) use ($columnName, $condition, $filterValue, $clause) {
            $this->resolveQuery($query, $columnName, $condition, $filterValue);

            if (! is_null($clause)) {
                $query->$clause($columnName);
            }
        });
    }

    /**
     * Check filter value condition.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  string                          $columnName
     * @param  string                          $condition
     * @param  string                          $filterValue
     * @param  bool                            $nullCheck
     * @return void
     */
    private function checkFilterValueCondition($collection, $columnName, $condition, $filterValue, $nullCheck = false)
    {
        $filterValue == 1
            ? $this->resolveFilterQuery($collection, $columnName, $condition, $filterValue, $nullCheck)
            : $this->resolveFilterQuery($collection, $columnName, $condition, $filterValue, ! $nullCheck);
    }
}