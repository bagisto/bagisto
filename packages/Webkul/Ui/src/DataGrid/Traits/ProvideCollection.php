<?php

namespace Webkul\Ui\DataGrid\Traits;

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
                $this->collection = $this->queryBuilder,
                $queryStrings
            );

            return $this->generateResults($filteredOrSortedCollection);
        }

        return $this->collection = $this->generateResults($this->queryBuilder);
    }

    /**
     * Finalyze your collection here.
     *
     * @return void
     */
    public function formatCollection()
    {
        $this->collection->transform(function ($record, $key) {
            foreach($this->columns as $column) {
                if (isset($column['wrapper'])) {
                    if (isset($column['closure']) && $column['closure'] == true) {
                        $record->{$column['index']} = $column['wrapper']($record);
                    } else {
                        $record->{$column['index']} = $column['wrapper']($record);
                    }
                } else {
                    if ($column['type'] == 'price') {
                        if (isset($column['currencyCode'])) {
                            $record->{$column['index']} = core()->formatPrice($record->{$column['index']}, $column['currencyCode']);
                        } else {
                            $record->{$column['index']} = core()->formatBasePrice($record->{$column['index']});
                        }
                    }
                }
            }

            foreach($this->actions as $action) {
                $toDisplay = (isset($action['condition']) && gettype($action['condition']) == 'object') ? $action['condition']($record) : true;

                $toDisplayKey = strtolower($action['title']) . '_to_display';
                $record->$toDisplayKey = $toDisplay;

                if ($toDisplay) {
                    if ($action['method'] == 'GET') {
                        $urlKey = strtolower($action['title']) . '_url';
                        $record->$urlKey = route($action['route'], $record->{$action['index'] ?? $this->index});
                    }
                }
            }

            return $record;
        });
    }

    /**
     * Sort or filter collection.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  array                           $parseInfo
     * @return \Illuminate\Support\Collection
     */
    public function sortOrFilterCollection($collection, $parseInfo)
    {
        foreach ($parseInfo as $key => $info) {
            $columnType = $this->findColumnType($key)[0] ?? null;
            $columnName = $this->findColumnType($key)[1] ?? null;

            if ($key === 'sort') {
                $this->sortCollection($collection, $info);
            } else if ($key === 'search') {
                $this->searchCollection($collection, $info);
            } else {
                if ($this->exceptionCheckInColumns($columnName)) {
                    return $collection;
                }

                $this->filterCollection($collection, $info, $columnType, $columnName);
            }
        }

        return $collection;
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
        } else {
            return $this->defaultResults($queryBuilderOrCollection);
        }
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
     * @param  array                           $info
     * @return void
     */
    private function sortCollection($collection, $info)
    {
        $countKeys = count(array_keys($info));

        if ($countKeys > 1) {
            throw new \Exception('Fatal Error! Multiple sort keys found, please resolve the URL manually.');
        }

        $columnName = $this->findColumnType(array_keys($info)[0]);

        $collection->orderBy(
            $columnName[1],
            array_values($info)[0]
        );
    }

    /**
     * Search collection.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @param  array                           $info
     * @return void
     */
    private function searchCollection($collection, $info)
    {
        $countKeys = count(array_keys($info));

        if ($countKeys > 1) {
            throw new \Exception('Multiple search keys found, please resolve the URL manually.');
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
     * @param  array                           $info
     * @param  string                          $columnType
     * @param  string                          $columnName
     * @return void
     */
    private function filterCollection($collection, $info, $columnType, $columnName)
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
     * Some exceptions check in column details.
     *
     * @param  string                          $columnName
     * @return bool
     */
    private function exceptionCheckInColumns($columnName)
    {
        foreach ($this->completeColumnDetails as $column) {
            if ($column['index'] === $columnName && ! $column['filterable']) {
                return true;
            }
        }

        return false;
    }
}