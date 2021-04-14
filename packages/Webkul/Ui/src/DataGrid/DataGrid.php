<?php

namespace Webkul\Ui\DataGrid;

use Illuminate\Support\Facades\Event;

abstract class DataGrid
{
    /**
     * Set index columns, ex: id.
     *
     * @var int
     */
    protected $index;

    /**
     * Default sort order of datagrid.
     *
     * @var string
     */
    protected $sortOrder = 'asc';

    /**
     * Situation handling property when working with custom columns in datagrid, helps abstaining
     * aliases on custom column.
     *
     * @var bool
     */
    protected $enableFilterMap = false;

    /**
     * This is array where aliases and custom column's name are passed.
     *
     * @var array
     */
    protected $filterMap = [];

    /**
     * Array to hold all the columns which will be displayed on frontend.
     *
     * @var array
     */
    protected $columns = [];


    /**
     * Complete column details.
     *
     * @var array
     */
    protected $completeColumnDetails = [];

    /**
     * Hold query builder instance of the query prepared by executing datagrid
     * class method `setQueryBuilder`.
     *
     * @var object
     */
    protected $queryBuilder;

    /**
     * Final result of the datagrid program that is collection object.
     *
     * @var array
     */
    protected $collection = [];

    /**
     * Set of handly click tools which you could be using for various operations.
     * ex: dyanmic and static redirects, deleting, etc.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Works on selection of values index column as comma separated list as response
     * to your endpoint set as route.
     *
     * @var array
     */
    protected $massActions = [];

    /**
     * Parsed value of the url parameters.
     *
     * @var array
     */
    protected $parse;

    /**
     * To show mass action or not.
     *
     * @var bool
     */
    protected $enableMassAction = false;

    /**
     * To enable actions or not.
     */
    protected $enableAction = false;

    /**
     * Paginate the collection or not.
     *
     * @var bool
     */
    protected $paginate = true;

    /**
     * If paginated then value of pagination.
     *
     * @var int
     */
    protected $itemsPerPage = 10;

    /**
     * Operators mapping.
     *
     * @var array
     */
    protected $operators = [
        'eq'       => '=',
        'lt'       => '<',
        'gt'       => '>',
        'lte'      => '<=',
        'gte'      => '>=',
        'neqs'     => '<>',
        'neqn'     => '!=',
        'eqo'      => '<=>',
        'like'     => 'like',
        'blike'    => 'like binary',
        'nlike'    => 'not like',
        'ilike'    => 'ilike',
        'and'      => '&',
        'bor'      => '|',
        'regex'    => 'regexp',
        'notregex' => 'not regexp',
    ];

    /**
     * Bindings.
     *
     * @var array
     */
    protected $bindings = [
        0 => 'select',
        1 => 'from',
        2 => 'join',
        3 => 'where',
        4 => 'having',
        5 => 'order',
        6 => 'union',
    ];

    /**
     * Select components.
     *
     * @var array
     */
    protected $selectcomponents = [
        0  => 'aggregate',
        1  => 'columns',
        2  => 'from',
        3  => 'joins',
        4  => 'wheres',
        5  => 'groups',
        6  => 'havings',
        7  => 'orders',
        8  => 'limit',
        9  => 'offset',
        10 => 'lock',
    ];

    /**
     * Contains the keys for which extra filters to show.
     *
     * @var string[]
     */
    protected $extraFilters = [];

    /**
     * The current admin user.
     *
     * @var object
     */
    protected $currentUser;

    abstract public function prepareQueryBuilder();

    abstract public function addColumns();

    /**
     * Create datagrid instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->invoker = $this;

        $this->currentUser = auth()->guard('admin')->user();
    }

    /**
     * Add the index as alias of the column and use the column to make things happen.
     *
     * @param string $alias
     * @param string $column
     *
     * @return void
     */
    public function addFilter($alias, $column)
    {
        $this->filterMap[$alias] = $column;

        $this->enableFilterMap = true;
    }

    /**
     * Add column.
     *
     * @param string $column
     *
     * @return void
     */
    public function addColumn($column)
    {
        $this->fireEvent('add.column.before.' . $column['index']);

        $this->columns[] = $column;

        $this->setCompleteColumnDetails($column);

        $this->fireEvent('add.column.after.' . $column['index']);
    }

    /**
     * Set complete column details.
     *
     * @param string $column
     *
     * @return void
     */
    public function setCompleteColumnDetails($column)
    {
        $this->completeColumnDetails[] = $column;
    }

    /**
     * Set query builder.
     *
     * @param \Illuminate\Database\Query\Builder $queryBuilder
     *
     * @return void
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Add action. Some datagrids are used in shops also. So extra
     * parameters is their. If needs to give an access just pass true
     * in second param.
     *
     * @param  array $action
     *
     * @param  bool  $specialPermission
     *
     * @return void
     */
    public function addAction($action, $specialPermission = false)
    {
        $currentRouteACL = $this->fetchCurrentRouteACL($action);

        $eventName = isset($action['title']) ? $this->generateEventName($action['title']) : null;

        if (bouncer()->hasPermission($currentRouteACL['key'] ?? null) || $specialPermission) {
            $this->fireEvent('action.before.' . $eventName);

            $this->actions[] = $action;
            $this->enableAction = true;

            $this->fireEvent('action.after.' . $eventName);
        }
    }

    /**
     * Add mass action. Some datagrids are used in shops also. So extra
     * parameters is their. If needs to give an access just pass true
     * in second param.
     *
     * @param array $massAction
     *
     * @param  bool  $specialPermission
     *
     * @return void
     */
    public function addMassAction($massAction, $specialPermission = false)
    {
        $massAction['route'] = $this->getRouteNameFromUrl($massAction['action'], $massAction['method']);

        $currentRouteACL = $this->fetchCurrentRouteACL($massAction);

        $eventName = isset($massAction['label']) ? $this->generateEventName($massAction['label']) : null;

        if (bouncer()->hasPermission($currentRouteACL['key'] ?? null) || $specialPermission) {
            $this->fireEvent('mass.action.before.' . $eventName);

            $this->massActions[] = $massAction;
            $this->enableMassAction = true;

            $this->fireEvent('mass.action.after.' . $eventName);
        }
    }

    /**
     * Get collections.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCollection()
    {
        $parsedUrl = $this->getQueryStrings();

        foreach ($parsedUrl as $key => $value) {
            if ($key === 'locale') {
                if (!is_array($value)) {
                    unset($parsedUrl[$key]);
                }
            } elseif (!is_array($value)) {
                unset($parsedUrl[$key]);
            }
        }

        if (count($parsedUrl)) {
            $filteredOrSortedCollection = $this->sortOrFilterCollection(
                $this->collection = $this->queryBuilder,
                $parsedUrl
            );

            if ($this->paginate) {
                if ($this->itemsPerPage > 0) {
                    return $filteredOrSortedCollection->orderBy(
                        $this->index,
                        $this->sortOrder
                    )->paginate($this->itemsPerPage)->appends(request()->except('page'));
                }
            } else {
                return $filteredOrSortedCollection->orderBy($this->index, $this->sortOrder)->get();
            }
        }

        if ($this->paginate) {
            if ($this->itemsPerPage > 0) {
                $this->collection = $this->queryBuilder->orderBy(
                    $this->index,
                    $this->sortOrder
                )->paginate($this->itemsPerPage)->appends(request()->except('page'));
            }
        } else {
            $this->collection = $this->queryBuilder->orderBy($this->index, $this->sortOrder)->get();
        }

        return $this->collection;
    }

    /**
     * To find the alias of the column and by taking the column name.
     *
     * @param array $columnAlias
     *
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
     * Sort or filter collection.
     *
     * @param \Illuminate\Support\Collection $collection
     * @param array                          $parseInfo
     *
     * @return \Illuminate\Support\Collection
     */
    public function sortOrFilterCollection($collection, $parseInfo)
    {
        foreach ($parseInfo as $key => $info) {
            $columnType = $this->findColumnType($key)[0] ?? null;
            $columnName = $this->findColumnType($key)[1] ?? null;

            if ($key === 'sort') {
                $count_keys = count(array_keys($info));

                if ($count_keys > 1) {
                    throw new \Exception('Fatal Error! Multiple Sort keys Found, Please Resolve the URL Manually');
                }

                $columnName = $this->findColumnType(array_keys($info)[0]);

                $collection->orderBy(
                    $columnName[1],
                    array_values($info)[0]
                );
            } elseif ($key === 'search') {
                $count_keys = count(array_keys($info));

                if ($count_keys > 1) {
                    throw new \Exception('Multiple Search keys Found, Please Resolve the URL Manually');
                }

                if ($count_keys == 1) {
                    $collection->where(function ($collection) use ($info) {
                        foreach ($this->completeColumnDetails as $column) {
                            if ($column['searchable'] == true) {
                                if ($this->enableFilterMap && isset($this->filterMap[$column['index']])) {
                                    $collection->orWhere(
                                        $this->filterMap[$column['index']],
                                        'like',
                                        '%' . $info['all'] . '%'
                                    );
                                } elseif ($this->enableFilterMap && !isset($this->filterMap[$column['index']])) {
                                    $collection->orWhere($column['index'], 'like', '%' . $info['all'] . '%');
                                } else {
                                    $collection->orWhere($column['index'], 'like', '%' . $info['all'] . '%');
                                }
                            }
                        }
                    });
                }
            } else {
                foreach ($this->completeColumnDetails as $column) {
                    if ($column['index'] === $columnName && !$column['filterable']) {
                        return $collection;
                    }
                }

                if (array_keys($info)[0] === 'like' || array_keys($info)[0] === 'nlike') {
                    foreach ($info as $condition => $filter_value) {
                        if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
                            $collection->where(
                                $this->filterMap[$columnName],
                                $this->operators[$condition],
                                '%' . $filter_value . '%'
                            );
                        } elseif ($this->enableFilterMap && !isset($this->filterMap[$columnName])) {
                            $collection->where(
                                $columnName,
                                $this->operators[$condition],
                                '%' . $filter_value . '%'
                            );
                        } else {
                            $collection->where(
                                $columnName,
                                $this->operators[$condition],
                                '%' . $filter_value . '%'
                            );
                        }
                    }
                } else {
                    foreach ($info as $condition => $filter_value) {

                        if ($condition === 'undefined') {
                            $condition = '=';
                        }

                        if ($columnType === 'datetime') {
                            if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
                                $collection->whereDate(
                                    $this->filterMap[$columnName],
                                    $this->operators[$condition],
                                    $filter_value
                                );
                            } elseif ($this->enableFilterMap && !isset($this->filterMap[$columnName])) {
                                $collection->whereDate(
                                    $columnName,
                                    $this->operators[$condition],
                                    $filter_value
                                );
                            } else {
                                $collection->whereDate(
                                    $columnName,
                                    $this->operators[$condition],
                                    $filter_value
                                );
                            }
                        } elseif ($columnType === 'boolean') {
                            if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
                                if ($this->operators[$condition] == '=') {
                                    if ($filter_value == 1) {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $this->filterMap[$columnName],
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNotNull($this->filterMap[$columnName]);
                                        });
                                    } else {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $this->filterMap[$columnName],
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNull($this->filterMap[$columnName]);
                                        });
                                    }
                                } elseif ($this->operators[$condition] == '<>') {
                                    if ($filter_value == 1) {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $this->filterMap[$columnName],
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNull($this->filterMap[$columnName]);
                                        });
                                    } else {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $this->filterMap[$columnName],
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNotNull($this->filterMap[$columnName]);
                                        });
                                    }
                                } else {
                                    $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                        $query->where(
                                            $this->filterMap[$columnName],
                                            $this->operators[$condition],
                                            $filter_value
                                        );
                                    });
                                }
                            } elseif ($this->enableFilterMap && !isset($this->filterMap[$columnName])) {
                                if ($this->operators[$condition] == '=') {
                                    if ($filter_value == 1) {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $columnName,
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNotNull($this->filterMap[$columnName]);
                                        });
                                    } else {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $columnName,
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNull($this->filterMap[$columnName]);
                                        });
                                    }
                                } elseif ($this->operators[$condition] == '<>') {
                                    if ($filter_value == 1) {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $columnName,
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNull($this->filterMap[$columnName]);
                                        });
                                    } else {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $columnName,
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNotNull($this->filterMap[$columnName]);
                                        });
                                    }
                                } else {
                                    $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                        $query->where(
                                            $columnName,
                                            $this->operators[$condition],
                                            $filter_value
                                        );
                                    });
                                }
                            } else {
                                if ($this->operators[$condition] == '=') {
                                    if ($filter_value == 1) {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $columnName,
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNotNull($this->filterMap[$columnName]);
                                        });
                                    } else {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $columnName,
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNull($this->filterMap[$columnName]);
                                        });
                                    }
                                } elseif ($this->operators[$condition] == '<>') {
                                    if ($filter_value == 1) {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $columnName,
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNull($this->filterMap[$columnName]);
                                        });
                                    } else {
                                        $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                            $query->where(
                                                $columnName,
                                                $this->operators[$condition],
                                                $filter_value
                                            )->orWhereNotNull($this->filterMap[$columnName]);
                                        });
                                    }
                                } else {
                                    $collection->Where(function ($query) use ($columnName, $condition, $filter_value) {
                                        $query->where(
                                            $columnName,
                                            $this->operators[$condition],
                                            $filter_value
                                        );
                                    });
                                }
                            }
                        } else {
                            if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
                                $collection->where(
                                    $this->filterMap[$columnName],
                                    $this->operators[$condition],
                                    $filter_value
                                );
                            } elseif ($this->enableFilterMap && !isset($this->filterMap[$columnName])) {
                                $collection->where(
                                    $columnName,
                                    $this->operators[$condition],
                                    $filter_value
                                );
                            } else {
                                $collection->where(
                                    $columnName,
                                    $this->operators[$condition],
                                    $filter_value
                                );
                            }
                        }
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * Preprare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
    }

    /**
     * Render view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        $necessaryExtraFilters = [];
        if (in_array('channels', $this->extraFilters)) {
            $necessaryExtraFilters['channels'] = core()->getAllChannels();
        }
        if (in_array('locales', $this->extraFilters)) {
            $necessaryExtraFilters['locales'] = core()->getAllLocales();
        }
        if (in_array('customer_groups', $this->extraFilters)) {
            $necessaryExtraFilters['customer_groups'] = core()->getAllCustomerGroups();
        }

        return view('ui::datagrid.table')->with('results', [
            'records'           => $this->getCollection(),
            'columns'           => $this->completeColumnDetails,
            'actions'           => $this->actions,
            'massactions'       => $this->massActions,
            'index'             => $this->index,
            'enableMassActions' => $this->enableMassAction,
            'enableActions'     => $this->enableAction,
            'paginated'         => $this->paginate,
            'itemsPerPage'      => $this->itemsPerPage,
            'norecords'         => __('ui::app.datagrid.no-records'),
            'extraFilters'      => $necessaryExtraFilters
        ]);
    }

    /**
     * Export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function export()
    {
        $this->paginate = false;

        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return $this->getCollection();
    }

    /**
     * Trigger event.
     *
     * @param string $name
     *
     * @return void
     */
    protected function fireEvent($name)
    {
        if (isset($name)) {
            $className = get_class($this->invoker);

            $className = last(explode('\\', $className));

            $className = strtolower($className);

            $eventName = $className . '.' . $name;

            Event::dispatch($eventName, $this->invoker);
        }
    }

    /**
     * Generate event name.
     *
     * @param  string  $titleOrLabel
     *
     * @return string
     */
    private function generateEventName($titleOrLabel)
    {
        $eventName = explode(' ', strtolower($titleOrLabel));
        return implode('.', $eventName);
    }

    /**
     * Parse the query strings and get it ready to be used.
     *
     * @return array
     */
    private function getQueryStrings()
    {
        $route = request()->route() ? request()->route()->getName() : '';

        $queryString = $this->grabQueryStrings($route == 'admin.datagrid.export' ? url()->previous() : url()->full());

        $parsedQueryStrings = $this->parseQueryStrings($queryString);

        $parsedQueryStrings = $this->updateQueryStrings($parsedQueryStrings);

        $this->itemsPerPage = isset($parsedQueryStrings['perPage']) ? $parsedQueryStrings['perPage']['eq'] : $this->itemsPerPage;

        unset($parsedQueryStrings['perPage']);

        return $parsedQueryStrings;
    }

    /**
     * Grab query strings from url.
     *
     * @param  string  $fullUrl
     *
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
     *
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
     *
     * @return array
     */
    private function updateQueryStrings($parsedQueryStrings)
    {
        if (isset($parsedQueryStrings['grand_total'])) {
            foreach ($parsedQueryStrings['grand_total'] as $key => $value) {
                $parsedQueryStrings['grand_total'][$key] = str_replace(',', '.', $parsedQueryStrings['grand_total'][$key]);
            }
        }

        return $parsedQueryStrings;
    }

    /**
     * Fetch current route acl. As no access to acl key, this will fetch acl by route name.
     *
     * @param  $action
     *
     * @return array
     */
    private function fetchCurrentRouteACL($action)
    {
        return collect(config('acl'))->filter(function ($acl) use ($action) {
            return $acl['route'] === $action['route'];
        })->first();
    }

    /**
     * Fetch route name from full url, not the current one.
     *
     * @param  $action
     *
     * @return array
     */
    private function getRouteNameFromUrl($action, $method)
    {
        return app('router')->getRoutes()
            ->match(app('request')->create(str_replace(url('/'), '', $action), $method))
            ->getName();
    }
}
