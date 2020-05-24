<?php

namespace Webkul\Ui\DataGrid;

use Illuminate\Support\Facades\Event;

abstract class DataGrid
{
    /**
     * set index columns, ex: id.
     * 
     * @var int
     */
    protected $index = null;

    /**
     * Default sort order of datagrid
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
     * This is array where aliases and custom column's name are passed
     * 
     * @var array
     */
    protected $filterMap = [];

    /**
     * array to hold all the columns which will be displayed on frontend.
     * 
     * @var array
     */
    protected $columns = [];


    /**
     * @var array
     */
    protected $completeColumnDetails = [];

    /**
     * Hold query builder instance of the query prepared by executing datagrid
     * class method setQueryBuilder
     * 
     * @var array
     */
    protected $queryBuilder = [];

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
     * Parsed value of the url parameters
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
     * paginate the collection or not
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
     * @var array
     */
    protected $operators = [
        'eq'       => "=",
        'lt'       => "<",
        'gt'       => ">",
        'lte'      => "<=",
        'gte'      => ">=",
        'neqs'     => "<>",
        'neqn'     => "!=",
        'eqo'      => "<=>",
        'like'     => "like",
        'blike'    => "like binary",
        'nlike'    => "not like",
        'ilike'    => "ilike",
        'and'      => "&",
        'bor'      => "|",
        'regex'    => "regexp",
        'notregex' => "not regexp",
    ];

    /**
     * @var array
     */
    protected $bindings = [
        0 => "select",
        1 => "from",
        2 => "join",
        3 => "where",
        4 => "having",
        5 => "order",
        6 => "union",
    ];

    /**
     * @var array
     */
    protected $selectcomponents = [
        0  => "aggregate",
        1  => "columns",
        2  => "from",
        3  => "joins",
        4  => "wheres",
        5  => "groups",
        6  => "havings",
        7  => "orders",
        8  => "limit",
        9  => "offset",
        10 => "lock",
    ];

    abstract public function prepareQueryBuilder();
    
    abstract public function addColumns();

    /**
     * @return void
     */
    public function __construct()
    {
        $this->invoker = $this;
    }

    /**
     * Parse the URL and get it ready to be used.
     * 
     * @return void
     */
    private function parseUrl()
    {
        $parsedUrl = [];
        $unparsed = url()->full();

        $route = request()->route() ? request()->route()->getName() : "";

        if ($route == 'admin.datagrid.export') {
            $unparsed = url()->previous();
        }

        if (count(explode('?', $unparsed)) > 1) {
            $to_be_parsed = explode('?', $unparsed)[1];

            parse_str($to_be_parsed, $parsedUrl);
            unset($parsedUrl['page']);
        }

        $this->itemsPerPage = isset($parsedUrl['perPage']) ? $parsedUrl['perPage']['eq'] : $this->itemsPerPage;

        unset($parsedUrl['perPage']);

        return $parsedUrl;
    }

    /**
     * Add the index as alias of the column and use the column to make things happen
     *
     * @param  string  $alias
     * @param  string  $column
     * @return void
     */
    public function addFilter($alias, $column) {
        $this->filterMap[$alias] = $column;

        $this->enableFilterMap = true;
    }

    /**
     * @param  string  $column
     * @return void
     */
    public function addColumn($column)
    {
        $this->fireEvent('add.column.before.'.$column['index']);

        array_push($this->columns, $column);

        $this->setCompleteColumnDetails($column);

        $this->fireEvent('add.column.after.'.$column['index']);
    }

    /**
     * @param  string  $column
     * @return void
     */
    public function setCompleteColumnDetails($column)
    {
        array_push($this->completeColumnDetails, $column);
    }

    /**
     * @param  \Illuminate\Database\Query\Builder  $queryBuilder
     * @return void
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param  array  $action
     * @return void
     */
    public function addAction($action)
    {
        if (isset($action['title'])) {
            $eventName = strtolower($action['title']);
            $eventName = explode(' ', $eventName);
            $eventName = implode('.', $eventName);
        } else {
            $eventName = null;
        }

        $this->fireEvent('action.before.'.$eventName);

        array_push($this->actions, $action);

        $this->enableAction = true;

        $this->fireEvent('action.after.' . $eventName);
    }

    /**
     * @param  array  $massAction
     * @return void
     */
    public function addMassAction($massAction)
    {
        if (isset($massAction['label'])) {
            $eventName = strtolower($massAction['label']);
            $eventName = explode(' ', $eventName);
            $eventName = implode('.', $eventName);
        } else {
            $eventName = null;
        }

        $this->fireEvent('mass.action.before.' . $eventName);

        array_push($this->massActions, $massAction);

        $this->enableMassAction = true;

        $this->fireEvent('mass.action.after.' . $eventName);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCollection()
    {
        $parsedUrl = $this->parseUrl();

        foreach ($parsedUrl as $key => $value) {
            if ( $key == 'locale') {
                if ( ! is_array($value)) {
                    unset($parsedUrl[$key]);
                }
            } elseif ( ! is_array($value)) {
                unset($parsedUrl[$key]);
            }
        }

        if (count($parsedUrl)) {
            $filteredOrSortedCollection = $this->sortOrFilterCollection($this->collection = $this->queryBuilder, $parsedUrl);

            if ($this->paginate) {
                if ($this->itemsPerPage > 0)
                    return $filteredOrSortedCollection->orderBy($this->index, $this->sortOrder)->paginate($this->itemsPerPage)->appends(request()->except('page'));
            } else {
                return $filteredOrSortedCollection->orderBy($this->index, $this->sortOrder)->get();
            }
        }

        if ($this->paginate) {
            if ($this->itemsPerPage > 0) {
                $this->collection = $this->queryBuilder->orderBy($this->index, $this->sortOrder)->paginate($this->itemsPerPage)->appends(request()->except('page'));
            }
        } else {
            $this->collection = $this->queryBuilder->orderBy($this->index, $this->sortOrder)->get();
        }

        return $this->collection;
    }

    /**
     * To find the alias of the column and by taking the column name.
     *
     * @param  array  $columnAlias
     * @return array
     */
    public function findColumnType($columnAlias)
    {
        foreach($this->completeColumnDetails as $column) {
            if($column['index'] == $columnAlias) {
                return [$column['type'], $column['index']];
            }
        }
    }

    /**
     * @param  \Illuminate\Support\Collection  $collection
     * @param  array  $parseInfo
     * @return \Illuminate\Support\Collection
     */
    public function sortOrFilterCollection($collection, $parseInfo)
    {
        foreach ($parseInfo as $key => $info)  {
            $columnType = $this->findColumnType($key)[0] ?? null;
            $columnName = $this->findColumnType($key)[1] ?? null;

            if ($key == "sort") {
                $count_keys = count(array_keys($info));

                if ($count_keys > 1) {
                    throw new \Exception('Fatal Error! Multiple Sort keys Found, Please Resolve the URL Manually');
                }

                $columnName = $this->findColumnType(array_keys($info)[0]);

                $collection->orderBy(
                    $columnName[1],
                    array_values($info)[0]
                );
            } elseif ($key == "search") {
                $count_keys = count(array_keys($info));

                if ($count_keys > 1) {
                    throw new \Exception('Multiple Search keys Found, Please Resolve the URL Manually');
                }

                if ($count_keys == 1) {
                    $collection->where(function($collection) use($info) {
                        foreach ($this->completeColumnDetails as $column) {
                            if ($column['searchable'] == true) {
                                if($this->enableFilterMap && isset($this->filterMap[$column['index']])) {
                                    $collection->orWhere($this->filterMap[$column['index']], 'like', '%'.$info['all'].'%');
                                } elseif($this->enableFilterMap && !isset($this->filterMap[$column['index']])) {
                                    $collection->orWhere($column['index'], 'like', '%'.$info['all'].'%');
                                }else {
                                    $collection->orWhere($column['index'], 'like', '%'.$info['all'].'%');
                                }
                            }
                        }
                    });
                }
            } else {
                foreach ($this->completeColumnDetails as $column) {
                    if($column['index'] == $columnName && !$column['filterable']) {
                        return $collection;
                    }
                }

                if (array_keys($info)[0] == "like" || array_keys($info)[0] == "nlike") {
                    foreach ($info as $condition => $filter_value) {
                        if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
                            $collection->where(
                                $this->filterMap[$columnName],
                                $this->operators[$condition],
                                '%'.$filter_value.'%'
                            );
                        } elseif ($this->enableFilterMap && ! isset($this->filterMap[$columnName])) {
                            $collection->where(
                                $columnName,
                                $this->operators[$condition],
                                '%'.$filter_value.'%'
                            );
                        } else {
                            $collection->where(
                                $columnName,
                                $this->operators[$condition],
                                '%'.$filter_value.'%'
                            );
                        }
                    }
                } else {
                    foreach ($info as $condition => $filter_value) {
                        if ($columnType == 'datetime') {
                            if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
                                $collection->whereDate(
                                    $this->filterMap[$columnName],
                                    $this->operators[$condition],
                                    $filter_value
                                );
                            } elseif ($this->enableFilterMap && ! isset($this->filterMap[$columnName])) {
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
                        } else {
                            if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
                                $collection->where(
                                    $this->filterMap[$columnName],
                                    $this->operators[$condition],
                                    $filter_value
                                );
                            } elseif($this->enableFilterMap && !isset($this->filterMap[$columnName])) {
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
     * @param  string  $name
     * @return void
     */
    protected function fireEvent($name)
    {
        if (isset($name)) {
            $className = get_class($this->invoker);

            $className = last(explode("\\", $className));

            $className = strtolower($className);

            $eventName = $className . '.' . $name;

            Event::dispatch($eventName, $this->invoker);
        }
    }

    /**
     * @return void
     */
    public function prepareMassActions()
    {
    }

    /**
     * @return void
     */
    public function prepareActions()
    {
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return view('ui::datagrid.table')->with('results', [
            'records'           => $this->getCollection(),
            'columns'           => $this->completeColumnDetails,
            'actions'           => $this->actions,
            'massactions'       => $this->massActions,
            'index'             => $this->index,
            'enableMassActions' => $this->enableMassAction,
            'enableActions'     => $this->enableAction,
            'paginated'         => $this->paginate,
            'norecords'         => trans('ui::app.datagrid.no-records'),
        ]);
    }

    /**
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
}
