<?php

namespace Webkul\Ui\DataGrid\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ProvideQueryResolver
{
	/**
	 * Main resolve method.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string                                $columnName
	 * @param string                                $condition
	 * @param string                                $filterValue
	 * @param string                                $clause
	 * @param string                                $method
	 * @return void
	 */
	private function resolve(Builder $query, string $columnName, string $condition, string $filterValue, string $clause = 'where',
							 string  $method = 'resolveQuery'): void
	{
        if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
            $this->$method($query, $this->filterMap[$columnName], $condition, $filterValue, $clause);
        } else if ($this->enableFilterMap && ! isset($this->filterMap[$columnName])) {
            $this->$method($query, $columnName, $condition, $filterValue, $clause);
        } else {
            $this->$method($query, $columnName, $condition, $filterValue, $clause);
        }
    }

	/**
	 * Resolve query.
	 *
	 * @param \Webkul\Product\Database\Eloquent\Builder $query
	 * @param string                                    $columnName
	 * @param string                                    $condition
	 * @param string                                    $filterValue
	 * @param string                                    $clause
	 * @return void
	 */
	private function resolveQuery(Builder $query, string $columnName, string $condition, string $filterValue, string $clause = 'where'): void
	{
		$query->$clause(
			$columnName,
			$this->operators[ $condition ],
			$filterValue
		);
	}

	/**
	 * Resolve boolean query.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string                                $columnName
	 * @param string                                $condition
	 * @param string                                $filterValue
	 * @return void
	 */
	private function resolveBooleanQuery(Builder $query, string $columnName, string $condition, string $filterValue): void
	{
        if ($this->operators[$condition] === '=') {
            $this->checkFilterValueCondition($query, $columnName, $condition, $filterValue);
        } else if ($this->operators[$condition] === '<>') {
            $this->checkFilterValueCondition($query, $columnName, $condition, $filterValue, true);
        } else {
            $this->resolveFilterQuery($query, $columnName, $condition, $filterValue);
        }
    }

	/**
	 * Resolve filter query.
	 *
	 * @param \Webkul\Product\Database\Eloquent\Builder $query
	 * @param string                                    $columnName
	 * @param string                                    $condition
	 * @param string                                    $filterValue
	 * @param null|bool                                 $nullCheck
	 * @return void
	 */
	private function resolveFilterQuery(Builder $query, string $columnName, string $condition, string $filterValue, ?bool $nullCheck = null): void
	{
        $clause = is_null($nullCheck) ? null : ( $nullCheck ? 'orWhereNull' : 'whereNotNull' );

        $query->where(function ($query) use ($columnName, $condition, $filterValue, $clause) {
            $this->resolveQuery($query, $columnName, $condition, $filterValue);

            if (! is_null($clause)) {
                $query->$clause($columnName);
            }
        });
    }

	/**
	 * Check filter value condition.
	 *
	 * @param \Webkul\Product\Database\Eloquent\Builder $query
	 * @param string                                    $columnName
	 * @param string                                    $condition
	 * @param string                                    $filterValue
	 * @param bool                                      $nullCheck
	 * @return void
	 */
	private function checkFilterValueCondition(Builder $query, string $columnName, string $condition, string $filterValue, bool $nullCheck = false): void
	{
		// TODO check type of $filterValue
		$filterValue === '1'
			? $this->resolveFilterQuery($query, $columnName, $condition, $filterValue, $nullCheck)
            : $this->resolveFilterQuery($query, $columnName, $condition, $filterValue, ! $nullCheck);
    }
}
