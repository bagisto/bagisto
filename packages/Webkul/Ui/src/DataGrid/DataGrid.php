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
        $name = $select = $aliased = $table = false;
        $join = $columns = $filterable = $searchable = $css = $operators = [];
        extract($args);
        return $this->build($name, $select, $filterable, $searchable, $aliased, $table, $join, $columns, $css, $operators);
    }

    //starts buikding the queries on the basis of selects, joins and filter with
    //attributes for class names and styles.

    public function build(
        $name = null,
        $select = false,
        array $filterable = [],
        array $searchable = [],
        bool $aliased = false,
        $table = null,
        array $join = [],
        array $columns = null,
        array $css = [],
        array $operators = [],
        Pagination $pagination = null
    ) {
        $this->request = Request::capture();
        $this->setName($name);
        $this->setSelect($select);
        $this->setFilterable($filterable);
        $this->setSearchable($filterable);
        $this->setAlias($aliased);
        $this->setTable($table);
        $this->setJoin($join);
        $this->addColumns($columns, true);
        $this->setCss($css);
        $this->setOperators($operators);
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
     * Set Searchable columns
     * @return $this
     */

    public function setSearchable($searchable)
    {
        $this->searchable = $searchable ?: [];
        return $this;
    }

    /**
     * Set alias parameter
     * to know whether
     * aliasing is true or not.
     *
     * @return $this.
     */

    public function setAlias(bool $aliased)
    {
        $this->aliased = $aliased ? : false;
    }

    /**
     * Set table name in front
     * of query scope.
     *
     * @return $this
     */

    public function setTable(string $table)
    {
        $this->table = $table ?: false;
        return $this;
    }

    /**
     * Set join bag if
     * present.
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

    public function setOperators(array $operators)
    {
        $this->operators = $operators ?: [];
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
        // the only use case remaining is making and testing the full validation and testing of
        // aliased case with alias used in column names also.
        if ($this->aliased) {
            //n of joins can lead to n number of aliases for columns and neglect the as for columns
            if (isset($_SERVER['QUERY_STRING'])) {
                $qr = $_SERVER['QUERY_STRING'];
                $parsed;
                parse_str($qr, $parsed);
            }
            foreach ($parsed as $k=>$v) {
                parse_str($v, $parsed[$k]);
            }
            // dump($parsed);
            foreach ($parsed as $key => $value) {
                foreach ($value as $column => $filter) {
                    if (array_keys($filter)[0]=="like") {
                        $this->query->where(
                            str_replace('_', '.', $column), //replace the logic of making the column name and consider the case for _ in column name already
                            $this->operators[array_keys($filter)[0]],
                            '%'.array_values($filter)[0].'%'
                        );
                    } elseif (array_keys($filter)[0]=="sort") {
                        $this->query->orderBy(
                            str_replace('_', '.', $column), //replace the logic of making the column name and consider the case for _
                            array_values($filter)[0]
                        );
                    } elseif ($column == "search") {
                        $this->query->where(function ($query) use ($filter){
                            foreach ($this->searchable as $search) {
                                $query->orWhere($search['column'], 'like', '%'.array_values($filter)[0].'%');
                            }

                        });

                    } else {
                        $this->query->where(
                        str_replace('_', '.', $column),
                        $this->operators[array_keys($filter)[0]],
                        array_values($filter)[0]
                    );
                    }
                }
            }
        } else {
            if (isset($_SERVER['QUERY_STRING'])) {
                $qr = $_SERVER['QUERY_STRING'];
                $parsed;
                parse_str($qr, $parsed);
            }

            foreach ($parsed as $k=>$v) {
                parse_str($v, $parsed[$k]);
            }
            foreach ($parsed as $key => $value) {
                foreach ($value as $column => $filter) {
                    if (array_keys($filter)[0]=="like") {
                        $this->query->where(
                            $column,
                            $this->operators[array_keys($filter)[0]],
                            '%'.array_values($filter)[0].'%'
                        );
                    } else {
                        $this->query->where(
                        $column,
                        $this->operators[array_keys($filter)[0]],
                        array_values($filter)[0]
                    );
                    }
                }
            }
        }
    }

    private function getDbQueryResults()
    {
        if (isset($_SERVER['QUERY_STRING'])) {
            $qr = $_SERVER['QUERY_STRING'];
            $parsed;
            parse_str($qr, $parsed);
        }


        if ($this->aliased==true) {
            //flags
            $table_alias = false;
            $join_table_alias = false;
            $allowed_joins = false;
            $other_joins = false;
            $join_good = false;

            //prepare query object
            $this->query = DB::table($this->table);

            //explode if alias is available
            if (strpos('.', $this->table)) {
                throw new \Exception("dot/s cannot be used in table names in mysql");
            } else {
                $exploded = explode('as', $this->table);
            }

            //check whether exploded string still has same table name
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
                    //Allow joins i.e left or right
                    if ($name=='leftjoin' || $name=='left join' || $name=='rightjoin' || $name=='right join') {

                        //check if the aliasing on the primary table and primaryKey in join is also the same
                        $primary_key_alias = trim(explode('.', $join['primaryKey'])[0]);

                        if ($primary_key_alias == $table_alias) {
                            $join_table_alias = explode('as', $join['table']);

                            if (isset($join_table_alias)) {
                                $alias1 = trim($join_table_alias[1]); //important!!!!!

                                //check if the secondary table match column is not having '.' and has proper alias
                                $secondary_join_column = $join['secondaryKey'];
                                if (isset($secondary_join_column)) {
                                    $exploded_secondary = explode('.', $secondary_join_column);
                                    $alias2 = trim($exploded_secondary[0]);
                                    if ($alias1 == $alias2) {

                                        //check whether secondary table columns are properly aliased
                                        $alias_proper_secondary = true;
                                        foreach ($this->columns as $column) {
                                            if ($x = explode('.', $column->name)[0]) {
                                                if (isset($x) && $x == $alias1) {
                                                    //check if this secondary column is using independent column alias
                                                    if (!strpos($column->name, 'as')) {
                                                        $alias_proper_secondary = false;
                                                    }
                                                }
                                            }
                                        }
                                        if ($alias_proper_secondary) {
                                            $this->getQueryWithJoin();
                                        } else {
                                            throw new \Exception('Due to a bug in laravel, you can\'t use secondary table columns without aliasing');
                                        }
                                    } else {
                                        throw new \Exception('Aliases of Join table and the secondary key columns do not match');
                                    }
                                } else {
                                    throw new \Exception('Improper aliasing on secondary/join column for join');
                                }
                            } else {
                                throw new \Exception('Join/Secondary table alias is not found for join');
                            }
                        } else {
                            throw new \Exception('Primary key and primary table aliases do not match for join');
                        }
                    } else {
                        $other_joins = true;
                        throw new \Exception('Please check if there is some fault in your aliasing and do not use as in column names or you might have been using a join that is not allowed i.e cross, inner, etc use left and right join only');
                    }
                }
            }

            //Check for column filter bags and resolve aliasing
            //run this if there are columns with filter bag
            $this->getQueryWithColumnFilters();

            //Run this if there are filters or sort params or range params in the urls
            if (isset($_SERVER['QUERY_STRING'])) {
                $qr = $_SERVER['QUERY_STRING'];
                $parsed;
                parse_str($qr, $parsed);
            }
            if (!empty($parsed)) {
                $this->getQueryWithFilters();
            } else {
                $this->results = $this->query->get();
                return $this->results;
            }
            $this->results = $this->query->get();
            return $this->results;
        } else {
            $this->query = DB::table($this->table);
            if (!empty($this->select)) {
                $this->getSelect();
            }
            $this->getQueryWithColumnFilters();
            if (isset($_SERVER['QUERY_STRING'])) {
                $qr = $_SERVER['QUERY_STRING'];
                $parsed;
                parse_str($qr, $parsed);
            }
            if (!empty($parsed)) {
                $this->getQueryWithFilters();
            } else {
                $this->results = $this->query->get();
                return $this->results;
            }
            $this->results = $this->query->get();
            return $this->results;
        }
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
            'filterable' =>$this->filterable,
            'operators' => $this->operators,
        ]);
    }
}
