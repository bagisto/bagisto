<?php

namespace Webkul\Ui\DataGrid\Traits;

use Illuminate\Support\Str;

trait ProvideCollection
{
    use ProvideQueryResolver, ProvideQueryStringParser;

    /**
     * Get collections.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCollection()
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
     * @param  \Illuminate\Support\Collection  $collection
     * @param  array  $parseInfo
     * @return \Illuminate\Support\Collection
     */
    public function sortOrFilterCollection($collection, $parseInfo)
    {
        foreach ($parseInfo as $key => $info) {
            $columnType = $this->findColumnType($key)[0] ?? null;

            $columnName = $this->findColumnType($key)[1] ?? null;

            if ($this->exceptionCheckInColumns($columnName)) {
                return $collection;
            }

            match($key) {
                'sort'   => $this->sortCollection($collection, $info),
                'search' => $this->searchCollection($collection, $info),
                default  => $this->filterCollection($collection, $info, $columnType, $columnName)
            };
        }

        return $collection;
    }

    /**
     * Finalize your collection here.
     *
     * @return void
     */
    public function formatCollection()
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
     * @param  array  $columnAlias
     * @return array
     */
    public function findColumnType($columnAlias)
    {
        foreach ($this->completeColumnDetails as $column) {
            if ($column['index'] == $columnAlias) {
                return [$column['type'], $column['index']];
            }
        }
    }

    /**
     * Generate full results.
     *
     * @param  object  $queryBuilderOrCollection
     * @return \Illuminate\Support\Collection
     */
    private function generateResults($queryBuilderOrCollection)
    {
        if ($this->paginate) {
            if ($this->itemsPerPage > 0) {
                return $this->paginatedResults($queryBuilderOrCollection);
            }
        }
        
        return $this->defaultResults($queryBuilderOrCollection);
    }

    /**
     * Generate paginated results.
     *
     * @param  object  $queryBuilderOrCollection
     * @return \Illuminate\Support\Collection
     */
    private function paginatedResults($queryBuilderOrCollection)
    {
        return $queryBuilderOrCollection->orderBy(
            $this->index,
            $this->sortOrder
        )->paginate($this->itemsPerPage)->appends(request()->except('page'));
    }

    /**
     * Generate default results.
     *
     * @param  object  $queryBuilderOrCollection
     * @return \Illuminate\Support\Collection
     */
    private function defaultResults($queryBuilderOrCollection)
    {
        return $queryBuilderOrCollection->orderBy($this->index, $this->sortOrder)->get();
    }

    /**
     * Sort collection.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  array  $info
     * @return void
     */
    private function sortCollection($collection, $info)
    {
        $availableOptions = ['asc', 'desc'];

        $selectedSortOption = strtolower(array_values($info)[0]);

        $countKeys = count(array_keys($info));

        if ($countKeys > 1) {
            throw new \Exception(__('ui::app.datagrid.error.multiple-sort-keys-error'));
        }

        $columnName = $this->findColumnType(array_keys($info)[0]);

        $collection->orderBy(
            $columnName[1],
            in_array($selectedSortOption, $availableOptions) ? $selectedSortOption : 'asc'
        );
    }

    /**
     * Search collection.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  array  $info
     * @return void
     */
    private function searchCollection($collection, $info)
    {
        $countKeys = count(array_keys($info));

        if ($countKeys > 1) {
            throw new \Exception(__('ui::app.datagrid.error.multiple-search-keys-error'));
        }

        if ($countKeys == 1) {
            $collection->where(function ($collection) use ($info) {
                foreach ($this->completeColumnDetails as $column) {
                    if ($column['searchable'] == true) {
                        $this->resolve($collection, $column['index'], 'like', '%' . $info['all'] . '%', 'orWhere');
                    }
                }
            });
        }
    }

    /**
     * Filter collection.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  array  $info
     * @param  string  $columnType
     * @param  string  $columnName
     * @return void
     */
    private function filterCollection($collection, $info, $columnType, $columnName)
    {
        if (
            array_keys($info)[0] === 'like'
            || array_keys($info)[0] === 'nlike'
        ) {
            foreach ($info as $condition => $filterValue) {
                $this->resolve($collection, $columnName, $condition, '%' . $filterValue . '%');
            }
        } else {
            foreach ($info as $condition => $filterValue) {

                $condition = ($condition === 'undefined') ? '=' : $condition;

                match($columnType) {
                    'boolean'  => $this->resolve($collection, $columnName, $condition, $filterValue, 'where', 'resolveBooleanQuery'),
                    'checkbox' => $this->resolve($collection, $columnName, $condition, $filterValue, 'whereIn', 'resolveCheckboxQuery'),
                    'price'    => $this->resolve($collection, $columnName, $condition, $filterValue, 'having'),
                    'datetime' => $this->resolve($collection, $columnName, $condition, $filterValue, 'whereDate'),
                default    => $this->resolve($collection, $columnName, $condition, $filterValue)
                };
            }
        }
    }

    /**
     * Transform your columns.
     *
     * @param  object  $record
     * @return void
     */
    private function transformColumns($record)
    {
        foreach ($this->columns as $column) {
            $supportedClosureKey = ['wrapper', 'closure'];

            $isClosure = ! empty(array_intersect($supportedClosureKey, array_keys($column)));

            if ($isClosure) {
                /**
                 * @deprecated $column['wrapper']
                 *
                 * Use $column['closure'] instead. `wrapper` key will get removed in the later version.
                 */
                if (
                    isset($column['wrapper'])
                    && gettype($column['wrapper']) === 'object'
                    && $column['wrapper'] instanceof \Closure
                ) {
                    if (! empty($column['closure'])) {
                        $record->{$column['index']} = $column['wrapper']($record);
                    } else {
                        $record->{$column['index']} = htmlspecialchars($column['wrapper']($record));
                    }
                } elseif (
                    isset($column['closure'])
                    && gettype($column['closure']) === 'object'
                    && $column['closure'] instanceof \Closure
                ) {
                    $record->{$column['index']} = $column['closure']($record);
                }
            } elseif ($column['type'] == 'datetime') {
                $record->{$column['index']} = core()->formatDate($record->{$column['index']}, $column['format'] ?? 'Y-m-d H:i:s');
            } elseif ($column['type'] == 'date') {
                $record->{$column['index']} = core()->formatDate($record->{$column['index']}, $column['format'] ?? 'Y-m-d');
            } else {
                if ($column['type'] == 'price') {
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
     * @parma  object  $record
     * @return void
     */
    private function transformActions($record)
    {
        foreach ($this->actions as $action) {
            $toDisplay = (isset($action['condition']) && gettype($action['condition']) == 'object')
                ? $action['condition']($record)
                : true;

            $toDisplayKey = $this->generateKeyFromActionTitle($action['title'], '_to_display');
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
     * @param  string  $columnName
     * @return bool
     */
    private function exceptionCheckInColumns($columnName)
    {
        foreach ($this->completeColumnDetails as $column) {
            if (
                $column['index'] === $columnName
                && ! $column['filterable']
            ) {                
                return true;
            }
        }

        return false;
    }

    /**
     * Generate unique key from title.
     *
     * @param  string  $title
     * @param  string  $suffix
     * @return string
     */
    private function generateKeyFromActionTitle($title, $suffix)
    {
        $validatedStrings = Str::slug($title, '_', app()->getLocale());

        return strtolower($validatedStrings) . $suffix;
    }
}
