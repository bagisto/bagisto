<?php

namespace Webkul\Ui\DataGrid;

use Event;

/**
 * DataGrid class
 *
 * @author    Prashant Singh <jitendra@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class DataGrid
{
    /**
     * set index columns, ex: id.
     */
    protected $index = null;

    /**
     * To know the class of datagrid calling parent methods, to be set by the extending class.
     */
    protected $invoker = null;

    /**
     * Default sort order of datagrid
     */
    protected $sortOrder = 'asc';

    /**
     * Situation handling property when working with custom columns in datagrid, helps abstaining
     * aliases on custom column.
     */
    protected $enableFilterMap = false;

    /**
     * This is array where aliases and custom column's name are passed
     */
    protected $filterMap = [];

    /**
     * array to hold all the columns which will be displayed on frontend.
     */
    protected $columns = [];

    protected $completeColumnDetails = [];

    /**
     * Hold query builder instance of the query prepared by executing datagrid
     * class method setQueryBuilder
     */
    protected $queryBuilder = [];

    /**
     * Final result of the datagrid program that is collection object.
     */
    protected $collection = [];

    /**
     * Set of handly click tools which you could be using for various operations.
     * ex: dyanmic and static redirects, deleting, etc.
     */
    protected $actions = [];

    /**
     * Works on selection of values index column as comma separated list as response
     * to your endpoint set as route.
     */
    protected $massActions = [];

    /**
     * Parsed value of the url parameters
     */
    protected $parse;

    /**
     * To show mass action or not.
     */
    protected $enableMassAction = false;

    /**
     * To enable actions or not.
     */
    protected $enableAction = false;

    /**
     * paginate the collection or not
     */
    protected $paginate = true;

    /**
     * If paginated then value of pagination.
     */
    protected $itemsPerPage = 15;

    protected $operators = [
        'eq' => "=",
        'lt' => "<",
        'gt' => ">",
        'lte' => "<=",
        'gte' => ">=",
        'neqs' => "<>",
        'neqn' => "!=",
        'eqo' => "<=>",
        'like' => "like",
        'blike' => "like binary",
        'nlike' => "not like",
        'ilike' => "ilike",
        'and' => "&",
        'bor' => "|",
        'regex' => "regexp",
        'notregex' => "not regexp"
    ];

    protected $bindings = [
        0 => "select",
        1 => "from",
        2 => "join",
        3 => "where",
        4 => "having",
        5 => "order",
        6 => "union"
    ];

    protected $selectcomponents = [
        0 => "aggregate",
        1 => "columns",
        2 => "from",
        3 => "joins",
        4 => "wheres",
        5 => "groups",
        6 => "havings",
        7 => "orders",
        8 => "limit",
        9 => "offset",
        10 => "lock"
    ];

    abstract public function prepareQueryBuilder();
    abstract public function addColumns();

    public function __construct()
    {
        $this->invoker = $this;
    }

    /**
     * Parse the URL and get it ready to be used.
     */
    private function parseUrl()
    {
        $parsedUrl = [];
        $unparsed = url()->full();

        if (count(explode('?', $unparsed)) > 1) {
            $to_be_parsed = explode('?', $unparsed)[1];

            parse_str($to_be_parsed, $parsedUrl);
            unset($parsedUrl['page']);
        }

        return $parsedUrl;
    }

    /**
     * Add the index as alias of the column and use the column to make things happen
     *
     * @return void
     */
    public function addFilter($alias, $column) {
        $this->filterMap[$alias] = $column;

        $this->enableFilterMap = true;
    }

    public function addColumn($column)
    {
        $this->fireEvent('add.column.before.'.$column['index']);

        array_push($this->columns, $column);

        $this->setCompleteColumnDetails($column);

        $this->fireEvent('add.column.after.'.$column['index']);
    }

    public function setCompleteColumnDetails($column)
    {
        array_push($this->completeColumnDetails, $column);
    }

    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

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

    public function getCollection()
    {
        $parsedUrl = $this->parseUrl();

        foreach ($parsedUrl as $key => $value) {
            if ( $key == 'locale') {
                if ( ! is_array($value)) {
                    unset($parsedUrl[$key]);
                }
            } else if ( ! is_array($value)) {
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
     * @return string
     */
    public function findColumnType($columnAlias)
    {
        foreach($this->completeColumnDetails as $column) {
            if($column['index'] == $columnAlias) {
                return [$column['type'], $column['index']];
            }
        }
    }

    public function sortOrFilterCollection($collection, $parseInfo)
    {
        foreach ($parseInfo as $key => $info)  {
            $columnType = $this->findColumnType($key)[0];
            $columnName = $this->findColumnType($key)[1];

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
            } else if ($key == "search") {
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
                                } else if($this->enableFilterMap && !isset($this->filterMap[$column['index']])) {
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
                        } else if ($this->enableFilterMap && ! isset($this->filterMap[$columnName])) {
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
                            } else if ($this->enableFilterMap && ! isset($this->filterMap[$columnName])) {
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
                            } else if($this->enableFilterMap && !isset($this->filterMap[$columnName])) {
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

    protected function fireEvent($name)
    {
        if (isset($name)) {
            $className = get_class($this->invoker);

            $className = last(explode("\\", $className));

            $className = strtolower($className);

            $eventName = $className . '.' . $name;

            Event::fire($eventName, $this->invoker);
        }
    }

    public function prepareMassActions() {
    }

    public function prepareActions() {
    }

    public function render()
    {
        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return view('ui::datagrid.table')->with('results', ['records' => $this->getCollection(), 'columns' => $this->completeColumnDetails, 'actions' => $this->actions, 'massactions' => $this->massActions, 'index' => $this->index, 'enableMassActions' => $this->enableMassAction, 'enableActions' => $this->enableAction, 'paginated' => $this->paginate, 'norecords' => trans('ui::app.datagrid.no-records')]);
    }

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
