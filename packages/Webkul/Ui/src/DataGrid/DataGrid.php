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

        Separates the bags in the array of make attributes
    }
    */

    public function make($args)
    {
        // list($name, $select, $table, $join, $columns) = array_values($args);
        $name = $select = $filterable = $table = false;
        $join = $columns = $css = $verbs = [];
        extract($args);
        return $this->build($name, $select, $filterable, $table, $join, $columns, $css, $verbs);
    }

    //starts buikding the queries on the basis of selects, joins and filter with
    //attributes for class names and styles.

    public function build(
        $name = null,
        $select = false,
        array $filterable = [],
        $table = null,
        array $join = [],
        array $columns = null,
        array $css = [],
        array $verbs = [],
        Pagination $pagination = null
    ) {
        $this->request = Request::capture();

        $this->setName($name);
        $this->setSelect($select);
        $this->setFilterable($filterable);
        $this->setTable($table);
        $this->setJoin($join);
        $this->addColumns($columns, true);
        $this->setCss($css);
        $this->setVerbs($verbs);
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
     * Set Filterable
     * @return $this
     */

    public function setFilterable(array $filterable)
    {
        $this->filterable = $filterable ?: [];
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

    /**
     * Adds the custom css rules
     * @retun $this
     */

    private function setCss($css = [])
    {
        $this->css = new Css($css);
        return $this->css;
    }

    /**
     * setFilterableColumns
     * @return $this
     */

    // public function setFilterableColumns($filterable_columns = [])
    // {
    //     $this->join = $filterable_columns ?: [];
    //     return $this;
    // }

    /**
     * Add Columns.
     *
     * @return $this
     */

    public function addColumns($columns = [], $reCreate = false)
    {
        if ($reCreate) {
            $this->columns = new Collection();
        }
        if ($columns) {
            foreach ($columns as $column) {
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
        if ($column instanceof Column) {
            $this->columns->push($column);
        } elseif (gettype($column) == 'array' && $column) {
            $this->columns->push(new Column($column, $this->request));
        } else {
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
        if ($column instanceof Column) {
            if ($multiple) {
                if ($this->columns->offsetExists($column->getName())) {
                    $this->columns->offsetSet($column->getName(). time(), $column);
                } else {
                    $this->columns->offsetSet($column->getName(), $column);
                }
            } else {
                $this->columns->offsetSet($column->getName(), $column);
            }
        } elseif (gettype($column) == 'array' && $column) {
            $columnObj = new Column($column);
            if ($multiple) {
                if ($this->columns->offsetExists($columnObj->getName())) {
                    $this->columns->offsetSet($columnObj->getName(). time(), $columnObj);
                } else {
                    $this->columns->offsetSet($columnObj->getName(), $columnObj);
                }
            } else {
                $this->columns->offsetSet($columnObj->getName(), $columnObj);
            }
        } else {
            throw new \Exception("DataGrid: Add Column argument is not valid!");
        }
        return $this;
    }

    /**
     * Adds expressional verbs to be used
     * @return $this
     */

    public function setVerbs(array $verbs)
    {
        $this->verbs = $verbs ?: [];
        return $this;
    }

    /**
     * Add Pagination.
     *
     * @return $this
     */

    public function addPagination($pagination = [])
    {
        if ($pagination instanceof Pagination) {
            $this->pagination = $pagination;
        } elseif (gettype($pagination) == 'array' && $pagination) {
            $this->pagination = new Pagination($pagination);
        } else {
            throw new \Exception("DataGrid: Pagination argument is not valid!");
        }
        return $this;
    }

    private function getSelect()
    {
        $select = [];
        foreach ($this->columns as $column) {
            $select[] = $column->name;
        }
        $this->query->select(...$select);
        if ($this->select) {
            $this->query->addselect($this->select);
        }
    }

    /**
     * ->join('contacts', 'users.id', '=', 'contacts.user_id')
     */

    private function getQueryWithJoin()
    {
        foreach ($this->join as $join) {
            $this->query->{$join['join']}($join['table'], $join['primaryKey'], $join['condition'], $join['secondaryKey']);
        }
    }

    private function getQueryWithColumnFilters()
    {
        foreach ($this->columns as $column) {
            if ($column->filter) { // if the filter bag in array exists then these will be applied.
                if (count($column->filter['condition']) == count($column->filter['condition'], COUNT_RECURSIVE)) {
                    $this->query->{$column->filter['function']}(...$column->filter['condition']);
                } else {
                    if (count($column->filter['condition']) == 3) {
                        $this->query->{$column->filter['function']}(
                            extract(
                                array_combine(
                                    // ['key', 'condition', 'value'],
                                    array_fill( //will work with all kind of where conditions
                                        0,
                                        (count($column->filter['condition']) - 1),
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
    }

    private function getQueryWithFilters()
    {
        //solve aliasing for table when as is used with table name
        //refer https://recursiveiterator.wordpress.com/2015/02/28/laravel-query-builder-dynamic-queries/
        //refer https://m.dotdev.co/writing-advanced-eloquent-search-query-filters-de8b6c2598db

        //New API Structure change

        //No kind of aliasing at all
        $filterable_columns = [];
        foreach ($this->filterable as $on_column) {
            array_push($filterable_columns, $on_column['column']);
        }
        $queried_columns = [];
        $qr = $_SERVER['QUERY_STRING'];
        $parsed;
        parse_str($qr, $parsed);
        foreach ($parsed as $key=>$value) {
            array_push($queried_columns, $key);
        }
        foreach ($filterable_columns as $fkey=>$fvalue) {
            //determines whether column is both filterable and query string is present for it.
            foreach ($queried_columns as $key=>$value) {
                if ($fvalue==$value) {
                    $conditions =$parsed[$value];
                    foreach ($conditions as $condition => $filter) {
                        $this->query->where(
                            $value,
                            $this->verbs[$condition],
                            $filter
                        );
                    }
                }
            }

            // if ($column->filterable) { //condition is required managing params from users i.e url or request
            //     $qr = $_SERVER['QUERY_STRING'];
            //     $col_name = $column->name;
            //     $col_name = str_replace(".", "_", $col_name);
            //     // dd($qr);
            //     dump($col_name);
            //     if ($columnFromfilterableRequest = $this->request->offsetGet($col_name)) {
            //         if ($filter = $columnFromRequest['filter']) {
            //             if ($condition = $columnFromRequest['condition']) {
            //                 $this->query->where(
            //                     $column->correctDotOnly(),
            //                     $condition,
            //                     $filter
            //                 );
            //             }
            //         }
            //     }
            // }
        }
        //follow a case where table is aliased and joins are not present
    }

    private function getDbQueryResults()
    {
        //flags
        $table_alias = false;
        $join_table_alias = false;
        $allowed_joins = false;
        $other_joins = false;
        $join_good = false;

        //prepare query object
        $this->query = DB::table($this->table);

        //explode if alias is available
        $exploded = explode('as', $this->table);
        // dd($exploded);
        if ($exploded[0]==$this->table) {
            $table_alias = false;
        } else { // (isset($exploded))
            $table_alias = true;
            $table_name = trim($exploded[0]);
            $table_alias = trim($exploded[1]);
        }

        //Run this if there are any selects priorly.
        if (!empty($this->select)) {
            $this->getSelect();
        }

        //Run this if there are joins
        if (!empty($this->join)) {
            foreach ($this->join as $join) {
                $name = strtolower($join['join']);
                if ($name=='leftjoin' || $name=='left join' || $name=='rightjoin' || $name=='right join') {

                    //check if the aliasing on the primary table and primaryKey in join is also the same
                    $primary_key_alias = trim(explode('.', $join['primaryKey'])[0]);

                    //got allowed joins i.e left or right
                    if ($primary_key_alias == $table_alias) {
                        $join_table_alias = explode('as', $join['table']);

                        if (isset($join_table_alias)) {
                            $alias1 = trim($join_table_alias[1]);
                            //check if the secondary table match column is not having '.' and has proper alias
                            $secondary_join_column = $join['secondaryKey'];

                            if (isset($secondary_join_column)) {
                                $exploded_secondary = explode('.', $secondary_join_column);
                                $alias2 = trim($exploded_secondary[0]);

                                if ($alias1 == $alias2) {
                                    $this->getQueryWithJoin();
                                } else {
                                    dd('Aliases of Join table and the secondary key columns do not match');
                                }
                            } else {
                                dd('improper aliasing of the join columns');
                            }
                        } else {
                            dd('join table alias or secondary table alias is not found');
                        }
                    } else {
                        dump($primary_key_alias, $table_alias);
                        dd('primary key and primary table aliases do not match');
                    }
                } else {
                    $other_joins = true;
                }
            }
        }

        //Check for column filter bags and resolve aliasing
        //run this if there are columns with filter bag
        $this->getQueryWithColumnFilters();

        //Run this if there are filters or sort params or range params in the urls
        $qr = $_SERVER['QUERY_STRING'];
        $parsed;
        //parse_url($qr, PHP_URL_QUERY)
        parse_str($qr, $parsed);
        if (!empty($parsed)) {
            dump('parsed url is not empty');
            // $filterable_columns = [];
            // foreach ($this->filterable as $on_column) {
            //     array_push($filterable_columns, $on_column['column']);
            // }
            $this->getQueryWithFilters();
        } else {
            dd('parsed url is empty');
        }

        // dump($this->query);
        $this->results = $this->query->get();
        return $this->results;
    }

    /**
     * @return view
     */

    public function render()
    {
        $this->getDbQueryResults();
        // dump($this->columns);
        return view('ui::datagrid.index', [
            'css' => $this->css,
            'results' => $this->results,
            'columns' => $this->columns,
        ]);
    }
}
