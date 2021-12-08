<?php

namespace Webkul\Ui\DataGrid\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

trait ProvideCollection
{
    use ProvideQueryResolver, ProvideQueryStringParser;

	/**
	 * Get collections.
	 *
	 * @throws \Exception
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
    public function getResults(): LengthAwarePaginator
	{
        $queryStrings = $this->getQueryStrings();

        if (count($queryStrings)) {
            $filteredOrSortedCollection = $this->sortOrFilterCollection(
                $this->queryBuilder,
                $queryStrings
            );

            return $this->collection = $this->generateResults($filteredOrSortedCollection);
        }

        return $this->collection = $this->generateResults($this->queryBuilder);
    }

	/**
	 * Sort or filter collection.
	 *
	 * @param \Illuminate\Database\Query\Builder $qrBuilder incoming query builder
	 * @param array                              $parseInfo
	 * @throws \Exception
	 * @return Builder sorted or filtered query builder
	 */
	public function sortOrFilterCollection(Builder $qrBuilder, array $parseInfo): Builder
	{
		foreach ( $parseInfo as $key => $info ) {
			$columnType = $this->findColumnType($key)[0] ?? null;
            $columnName = $this->findColumnType($key)[1] ?? null;

			switch ($key) {
				case 'sort': $this->sortCollection($qrBuilder, $info); break;
				case 'search': $this->searchCollection($qrBuilder, $info); break;
				default:
					if ($this->exceptionCheckInColumns($columnName)) {
						return $qrBuilder;
					}
					$this->filterCollection($qrBuilder, $info, $columnType, $columnName);
					break;
			}
        }

        return $qrBuilder;
    }

    /**
     * Finalize your collection here.
     *
     * @return void
     */
    public function formatCollection(): void
	{
        $this->collection->transform(function ($record) {
            $this->transformActions($record);

            $this->transformColumns($record);

            return $record;
        });
    }

    /**
     * To find the alias of the column and by taking the column name.
     *
     * @param string $columnAlias
     * @return null|array
     */
    public function findColumnType(string $columnAlias): ?array
	{
        foreach ($this->completeColumnDetails as $column) {
			if ($column['index'] === $columnAlias) {
				return [ $column['type'], $column['index'] ];
			}
        }
		return null;
    }

	/**
	 * Generate full results.
	 *
	 * @param \Illuminate\Database\Query\Builder $queryBuilderOrCollection
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
    private function generateResults(Builder $queryBuilderOrCollection): LengthAwarePaginator
	{
        if ($this->paginate && $this->itemsPerPage > 0) {
			return $this->paginatedResults($queryBuilderOrCollection);
		}

		return $this->defaultResults($queryBuilderOrCollection);
    }

	/**
	 * Generate paginated results.
	 *
	 * @param Builder $queryBuilderOrCollection
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
    private function paginatedResults(Builder $queryBuilderOrCollection): \Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
        return $queryBuilderOrCollection->orderBy(
            $this->index,
            $this->sortOrder
        )->paginate($this->itemsPerPage)->appends(request()->except('page'));
    }

    /**
	 * Generate default results.
	 * @param \Illuminate\Database\Query\Builder $queryBuilderOrCollection
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	private function defaultResults(Builder $queryBuilderOrCollection): LengthAwarePaginator
	{
		return $queryBuilderOrCollection->orderBy($this->index, $this->sortOrder)->paginate(15);
    }

	/**
	 * Sort collection.
	 *
	 * @param \Illuminate\Database\Query\Builder $query
	 * @param array                              $info
	 * @throws \Exception when more than one sort key is provided in "info"
	 * @return void
	 */
	private function sortCollection(Builder $query, array $info): void
	{
        $availableOptions = ['asc', 'desc'];

        $selectedSortOption = strtolower(array_values($info)[0]);

        $countKeys = count(array_keys($info));

        if ($countKeys > 1) {
            throw new \Exception(__('ui::app.datagrid.error.multiple-sort-keys-error'));
        }

        $columnName = $this->findColumnType(array_keys($info)[0]);

        $query->orderBy(
            $columnName[1],
            in_array($selectedSortOption, $availableOptions) ? $selectedSortOption : 'asc'
        );
    }

	/**
	 * Search collection.
	 *
	 * @param       $collection
	 * @param array $info
	 * @throws \Exception when $info contains more than one key
	 * @return void
	 */
	private function searchCollection(Builder $collection, array $info): void
	{
        $countKeys = count(array_keys($info));

        if ($countKeys > 1) {
            throw new \Exception(__('ui::app.datagrid.error.multiple-search-keys-error'));
        }

        if ($countKeys === 1) {
            $collection->where(function ($collection) use ($info) {
                foreach ($this->completeColumnDetails as $column) {
                    if (($column['searchable'] === 'true') || ($column['searchable'] === true)) {
                        $this->resolve($collection, $column['index'], 'like', '%' . $info['all'] . '%', 'orWhere');
                    }
                }
            });
        }
    }

	/**
	 * Filter collection.
	 *
	 * @param Builder $collection
	 * @param array   $info
	 * @param string  $columnType
	 * @param string  $columnName
	 * @return void
	 */
	private function filterCollection(Builder $collection, array $info, string $columnType, string $columnName): void
	{
		if (array_keys($info)[0] === 'like' || array_keys($info)[0] === 'nlike') {
            foreach ($info as $condition => $filterValue) {
                $this->resolve($collection, $columnName, $condition, '%' . $filterValue . '%');
            }
        } else {
            foreach ($info as $condition => $filterValue) {

                $condition = ($condition === 'undefined') ? '=' : $condition;

                if ($columnType === 'datetime') {
                    $this->resolve($collection, $columnName, $condition, $filterValue, 'whereDate');
                } else if ($columnType === 'boolean') {
                    $this->resolve($collection, $columnName, $condition, $filterValue, 'where', 'resolveBooleanQuery');
                } else {
                    $this->resolve($collection, $columnName, $condition, $filterValue);
                }
            }
        }
    }

	/**
	 * Transform your columns.
	 *
	 * @param object $record
	 * @return void
	 */
	private function transformColumns(object $record): void
	{
        foreach($this->columns as $column) {
			$supportedClosureKey = [ 'wrapper', 'closure' ];

			$isClosure = !empty(array_intersect($supportedClosureKey, array_keys($column)));

			if ($isClosure) {
				/**
				 * @deprecated $column['wrapper']
				 * Use $column['closure'] instead. `wrapper` key will get removed in the later version.
				 */
				if (isset($column['wrapper']) && $column['wrapper'] instanceof \Closure) {
					if (isset($column['closure']) && ($column['closure'] === true || $column['closure'] === 'true')) {
						$record->{$column['index']} = $column['wrapper']($record);
					} else {
						$record->{$column['index']} = htmlspecialchars($column['wrapper']($record));
					}
				} else if (isset($column['closure']) && $column['closure'] instanceof \Closure) {
					$record->{$column['index']} = $column['closure']($record);
				}
			}
			else {
				if ($column['type'] === 'price') {
					if (isset($column['currencyCode'])) {
						$record->{$column['index']} = htmlspecialchars(core()->formatPrice($record->{$column['index']}, $column['currencyCode']));
					} else {
						$record->{$column['index']} = htmlspecialchars(core()->formatBasePrice($record->{$column['index']}));
					}
				} else {
					$record->{$column['index']} = htmlspecialchars($record->{$column['index']});
				}
			}
        }
    }

	/**
	 * Transform your actions.
	 *
	 * @param $record
	 * @return void
	 */
	private function transformActions($record): void
	{
        foreach($this->actions as $action) {
            $toDisplay = (isset($action['condition']) && is_object($action['condition'])) ? $action['condition']($record) : true;

			$toDisplayKey          = $this->generateKeyFromActionTitle($action['title'], '_to_display');
			$record->$toDisplayKey = $toDisplay;

			if ($toDisplay) {
                $urlKey = $this->generateKeyFromActionTitle($action['title'], '_url');
                $record->$urlKey = route($action['route'], $record->{$action['index'] ?? $this->index});
            }
        }
    }

	/**
	 * Some exceptions check in column details.
	 *
	 * @param string $columnName
	 * @return bool
	 */
	private function exceptionCheckInColumns(string $columnName): bool
	{
		foreach ($this->completeColumnDetails as $column) {
            if ($column['index'] === $columnName && ! $column['filterable']) {
                return true;
            }
        }

        return false;
    }

	/**
	 * Generate unique key from title.
	 *
	 * @param string $title
	 * @param string $suffix
	 * @return string
	 */
	private function generateKeyFromActionTitle(string $title, string $suffix): string
	{
        $validatedStrings = Str::slug($title, '_');

        return strtolower($validatedStrings) . $suffix;
    }
}
