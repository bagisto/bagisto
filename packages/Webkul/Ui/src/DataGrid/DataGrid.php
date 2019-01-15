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
    protected $itemsPerPage = 10;

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

        if(count($parsedUrl)) {
            $filteredOrSortedCollection = $this->sortOrFilterCollection($this->collection = $this->queryBuilder, $parsedUrl);

            if(config('datagrid.paginate')) {
                if($this->itemsPerPage > 0)
                    return $filteredOrSortedCollection->paginate($this->itemsPerPage)->appends(request()->except('page'));
            } else {
                return $filteredOrSortedCollection->get();
            }
        }

        if(config('datagrid.paginate')) {
            if ($this->itemsPerPage > 0) {
                $this->collection = $this->queryBuilder->paginate($this->itemsPerPage)->appends(request()->except('page'));
            }
        } else {
            $this->collection = $this->queryBuilder->get();
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
            if($column['identifier'] == $columnAlias) {
                return [$column['type'], $column['index']];
            }
        }
    }

    public function sortOrFilterCollection($collection, $parseInfo)
    {
        foreach($parseInfo as $key => $info)  {
            $columnType = $this->findColumnType($key)[0];
            $columnName = $this->findColumnType($key)[1];

            if($key == "sort") {
                $count_keys = count(array_keys($info));

                if ($count_keys > 1) {
                    throw new \Exception('Fatal Error! Multiple Sort keys Found, Please Resolve the URL Manually');
                }

                $columnName = $this->findColumnType(array_keys($info)[0]);

                return $collection->orderBy(
                    $columnName[1],
                    array_values($info)[0]
                );
            } else if($key == "search") {
                $count_keys = count(array_keys($info));

                if($count_keys > 1) {
                    throw new \Exception('Multiple Search keys Found, Please Resolve the URL Manually');
                }

                if($count_keys == 1) {
                    return $collection->where(function() use($collection, $info) {
                        foreach ($this->completeColumnDetails as $column) {
                            if($column['searchable'] == true)
                                $collection->orWhere($column['index'], 'like', '%'.$info['all'].'%');
                        }
                    });
                }
            } else {
                if (array_keys($info)[0] == "like" || array_keys($info)[0] == "nlike") {
                    foreach ($info as $condition => $filter_value) {
                        return $collection->where(
                            $columnName,
                            config("datagrid.operators.{$condition}"),
                            '%'.$filter_value.'%'
                        );
                    }
                } else {
                    foreach ($info as $condition => $filter_value) {
                        if($columnType == 'datetime') {
                            return $collection->whereDate(
                                $columnName,
                                config("datagrid.operators.{$condition}"),
                                $filter_value
                            );
                        } else {
                            return $collection->where(
                                $columnName,
                                config("datagrid.operators.{$condition}"),
                                $filter_value
                            );
                        }
                    }
                }
            }
        }
    }

    public function render()
    {
        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return view('ui::datagrid.table')->with('results', ['records' => $this->getCollection(), 'columns' => $this->completeColumnDetails, 'actions' => $this->actions, 'massactions' => $this->massActions, 'index' => $this->index, 'enableMassActions' => $this->enableMassAction, 'enableActions' => $this->enableAction, 'norecords' => trans('ui::app.datagrid.no-records')]);
    }
}