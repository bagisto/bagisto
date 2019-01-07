<?php

namespace Webkul\Ui\DataGrid;

use Illuminate\Http\Request;
/**
 * Product Data Grid class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class AbsGrid
{
    protected $columns = [];

    protected $allColumns = [];

    protected $queryBuilder = [];

    protected $collection = [];

    protected $request;

    protected $parse;

    abstract public function prepareQueryBuilder();

    abstract public function addColumns();

    abstract public function render();

    /**
     * Parse the URL and get it ready to be used.
     */
    private function parse()
    {
        $parsed = [];
        $unparsed = url()->full();

        if (count(explode('?', $unparsed)) > 1) {
            $to_be_parsed = explode('?', $unparsed)[1];

            parse_str($to_be_parsed, $parsed);
            unset($parsed['page']);
        }

        return $parsed;
    }

    public function addColumn($column)
    {
        if (isset($column['alias'])) {
            array_push($this->columns, $column['column'].' as '. $column['alias']);
        } else {
            array_push($this->columns, $column['column']);
        }

        $this->setAllColumnDetails($column);
    }

    public function setAllColumnDetails($column)
    {
        array_push($this->allColumns, $column);
    }

    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function getCollection()
    {
        $p = $this->parse();

        if(count($p)) {
            $filteredOrSortedCollection = $this->sortOrFilterCollection($this->collection = $this->queryBuilder, $p);

            return $filteredOrSortedCollection->get();
        }

        if (config()->has('datagrid.pagination')) {
            $this->collection = $this->queryBuilder->paginate(config('datagrid.pagination'));
        } else {
            $this->collection = $this->queryBuilder->get();
        }

        if ($this->collection) {
            return $this->collection;
        } else {
            return $this->collection;
        }
    }

    public function sortOrFilterCollection($collection, $parseInfo) {
        foreach($parseInfo as $key => $info)  {
            // dd($key);

            if($key == "sort") {
                //case that don't need any resolving
                $count_keys = count(array_keys($info));

                if ($count_keys == 1) {
                    return $collection->orderBy(
                        str_replace('_', '.', array_keys($info)[0]),
                        array_values($info)[0]
                    );
                } else {
                    throw new \Exception('Multiple Sort keys Found, Please Resolve the URL Manually');
                }
            } else if($key == "search") {
                if ($count_keys == 1) {
                    $collection->orderBy(
                        str_replace('_', '.', array_keys($info)[0]),
                        array_values($info)[0]
                    );
                } else {
                    throw new \Exception('Multiple Search keys Found, Please Resolve the URL Manually');
                }
            } else {
                $column_name = $this->findColumnAlias($key);

                if (array_keys($value)[0] == "like" || array_keys($value)[0] == "nlike") {
                    foreach ($value as $condition => $filter_value) {
                        $collection->where(
                            $column_name,
                            $this->operators[$condition],
                            '%'.$filter_value.'%'
                        );
                    }
                } else {
                    foreach ($value as $condition => $filter_value) {
                        if($column_type == 'datetime') {
                            $collection->whereDate(
                                $column_name,
                                $this->operators[$condition],
                                $filter_value
                            );
                        } else {
                            $collection->where(
                                $column_name,
                                $this->operators[$condition],
                                $filter_value
                            );
                        }
                    }
                }
            }
        }
    }
}