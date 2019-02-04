<?php

namespace Webkul\Ui\DataGrid;

use Illuminate\Http\Request;

/**
 * DataGrid class
 *
 * @author    Prashant Singh <jitendra@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class DataGrid
{
    protected $index = null;
    protected $sortOrder = 'asc';
    protected $enableFilterMap = false;
    protected $filterMap = [];
    protected $columns = [];
    protected $completeColumnDetails = [];
    protected $queryBuilder = [];
    protected $collection = [];
    protected $actions = [];
    protected $massActions = [];
    protected $request;
    protected $parse;
    protected $enableMassAction = false;
    protected $enableAction = false;
    protected $paginate = true;
    protected $itemsPerPage = 10;
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
        array_push($this->columns, $column);

        $this->setCompleteColumnDetails($column);
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
        array_push($this->actions, $action);

        $this->enableAction = true;
    }

    public function addMassAction($massAction)
    {
        array_push($this->massActions, $massAction);

        $this->enableMassAction = true;
    }

    public function getCollection()
    {
        $parsedUrl = $this->parseUrl();

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
                                } else {
                                    $collection->orWhere($column['index'], 'like', '%'.$info['all'].'%');
                                }
                            }
                        }
                    });
                }
            } else {
                if (array_keys($info)[0] == "like" || array_keys($info)[0] == "nlike") {
                    foreach ($info as $condition => $filter_value) {
                        if ($this->enableFilterMap && isset($this->filterMap[$columnName])) {
                            $collection->where(
                                $this->filterMap[$columnName],
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
}