<?php

namespace Webkul\Ui\DataGrid;

use Illuminate\Http\Request;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

use Webkul\Ui\DataGrid\Helpers\Column;
use Webkul\Ui\DataGrid\Helpers\Pagination;
use Webkul\Ui\DataGrid\Helpers\Css;

class DataGrid
{   
    /**
     * Name of DataGrid
     *
     * @var string
     */
    protected $name;

    /**
     * select from table(s)
     *
     * @var string
     */
    protected $select;

    /**
     * Table
     *
     * @var String Classs name $table
     */
    protected $table;

    /**
     * Join
     *
     * @var Array name $join
     * 
     * [
     *      'join' => 'left',
     *      'table' => 'posts',
     *      'primaryKey' => 'user.id',
     *      'condition' => '=',
     *      'secondryKey' => 'posts.user_id',
     *      'callback' => 'not supported yet'
     * ]
     */
    protected $join;

    /**
     * Collection Object of Column $columns
     *
     * @var Collection
     */
    protected $columns;

    /**
     * Pagination $pagination
     *
     * @var Pagination
     */
    protected $pagination;

    /**
     * Css $css
     *
     * @var Css
     */
    protected $css;
    
    /*
    public function __construct(
        $name = null ,
        $table = null ,
        array $join = [],
        Collection $columns = null,
        Pagination $pagination = null
    ){
        $this->make(
            $name,
            $table,
            $join,
            $columns,
            $pagination
        );

        return $this;
    }
    */
    public function make($args){
        // list($name, $select, $table, $join, $columns) = array_values($args);
        $name = $select = $table = false;
        $join = $columns = $css = [];
        extract($args);
        return $this->build($name, $select, $table, $join, $columns, $css);
    }

    public function build(
        $name = null,
        $select = false, 
        $table = null,
        array $join = [],
        array $columns = null,
        array $css = [],
        Pagination $pagination = null
    ){
        $this->request = Request::capture();
        
        $this->setName($name);
        $this->setSelect($select);
        $this->setTable($table);
        $this->setJoin($join);

        $this->addColumns($columns, true);
        $this->setCss($css);
        // $this->addPagination($pagination);

        return $this;
    }

    /**
     * Set Name.
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name ?: 'Default' . time();
        return $this;
    }

    /**
     * Set Select.
     *
     * @return $this
     */
    public function setSelect($select)
    {
        $this->select = $select ?: false;
        return $this;
    }

    /**
     * Add Columns.
     *
     * @return $this
     */
    public function setTable(string $table)
    {
        $this->table = $table ?: false;
        return $this;
    }

    /**
     * Add Columns.
     *
     * @return $this
     */
    public function setJoin(array $join)
    {
        $this->join = $join ?: [];
        return $this;
    }

    private function setCss($css = [])
    {
        $this->css = new Css($css);
        return $this->css;
    }

    /**
     * Add Columns.
     *
     * @return $this
     */
    public function addColumns($columns = [], $reCreate = false)
    {
        if($reCreate) $this->columns = new Collection();

        if($columns){
            foreach($columns as $column){
                $this->addColumn($column);
            }
        }
        return $this;
    }

    /**
     * Add Column.
     *
     * @return $this
     */
    public function addColumn($column = [])
    {
        if($column instanceof Column){
            $this->columns->push($column);
        }elseif(gettype($column) == 'array' && $column){
            $this->columns->push(new Column($column, $this->request));
        }else{
            throw new \Exception("DataGrid: Add Column argument is not valid!");
        }

        return $this;
    }

    /**
     * Add ColumnMultiple.
     *
     * @return $this
     */
    private function addColumnMultiple($column = [], $multiple = false)
    {
        if($column instanceof Column){
            if($multiple){
                if($this->columns->offsetExists($column->getName()))
                    $this->columns->offsetSet($column->getName(). time(), $column);
                else
                    $this->columns->offsetSet($column->getName(), $column);
            }else{
                $this->columns->offsetSet($column->getName(), $column);
            }
        }elseif(gettype($column) == 'array' && $column){
            $columnObj = new Column($column);
            if($multiple){
                if($this->columns->offsetExists($columnObj->getName()))
                    $this->columns->offsetSet($columnObj->getName(). time(), $columnObj);
                else
                    $this->columns->offsetSet($columnObj->getName(), $columnObj);
            }else{
                $this->columns->offsetSet($columnObj->getName(), $columnObj);
            }
        }else{
            throw new \Exception("DataGrid: Add Column argument is not valid!");
        }

        return $this;
    }

    /**
     * Add Pagination.
     *
     * @return $this
     */
    public function addPagination($pagination = [])
    {
        if($pagination instanceof Pagination){
            $this->pagination = $pagination;
        }elseif(gettype($pagination) == 'array' && $pagination){
            $this->pagination = new Pagination($pagination);
        }else{
            throw new \Exception("DataGrid: Pagination argument is not valid!");
        }

        return $this;
    }

    private function getSelect()
    {
        $select = [];
        foreach($this->columns as $column){
            $select[] = $column->name;
        }

        $this->query->select(...$select);
        if($this->select) $this->query->addselect($this->select);
    }

    /**
     * ->join('contacts', 'users.id', '=', 'contacts.user_id')
     */
    private function getQueryWithJoin()
    {
        foreach($this->join as $join){
            $this->query->{$join['join']}($join['table'], $join['primaryKey'], $join['condition'], $join['secondryKey']);
        }
    }

    private function getQueryWithColumnFilters()
    {
        foreach($this->columns as $column){
            if($column->filter){
                if (count($column->filter['condition']) == count($column->filter['condition'], COUNT_RECURSIVE)){
                    $this->query->{$column->filter['function']}(current($column->filter['condition']));
                }else{
                    if(count($column->filter['condition']) == 3)
                        $this->query->{$column->filter['function']}(
                            extract(
                                array_combine(
                                    // ['key', 'condition', 'value'], 
                                    array_fill( //will work with all kind of where conditions
                                        0, 
                                        ( count( $column->filter['condition']) - 1 ),
                                        'array_fill_nikhil'.time()
                                    ),
                                    $column->filter['condition']
                                )
                            )
                        );
                }
            }
        }
    }

    private function getQueryWithFilters(){
        foreach($this->columns as $column){
            dd($this->request);
            if($column->filterable){
                if ($filter = $this->request->offsetGet($column->correct(false))){
                    if($condition = $this->request->offsetGet($column->correct(false).'.condition')){
                        $this->query->andwhere(
                            $this->correct(false),
                            $condition,
                            $filter
                        );
                    }
                }
            }
        }

        $query = ['sort' => $this->correct(false)];

        if(($sort = $this->request->offsetGet('sort')) && $sort == $this->correct(false)){
            if(!$order = $this->request->offsetGet('order')){
                $query['order'] = self::ORDER_DESC;
            }else{
                $query['order'] = ($order == self::ORDER_DESC ? self::ORDER_ASC : self::ORDER_DESC);
            }
        }else{
            $query['order'] = self::ORDER_DESC;
        }
        
        return '?'.http_build_query(array_merge($this->request->query->all(), $query));
    }

    private function getDbQueryResults()
    {
        $this->query = DB::table($this->table);

        $this->getSelect();
        $this->getQueryWithJoin();
        $this->getQueryWithColumnFilters();
        $this->getQueryWithFilters();

        dd($this->query);

        $this->results = $this->query->get();
        return $this->results;
    }

    /**
     * @return view
     */
    public function render()
    {   
        $this->getDbQueryResults();

        dump($this->columns);

        return view('ui::datagrid.index', [
            'css' => $this->css,
            'results' => $this->results,
            'columns' => $this->columns,
        ]);
    }
}
