<?php
namespace Webkul\Ui\DataGrid;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Validate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\Helpers\Column;
use Webkul\Ui\DataGrid\Helpers\Pagination;
use Webkul\Ui\DataGrid\Helpers\Css;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Models\ProductAttributeValue;
use URL;

/**
 * DataGrid controller
 *
 * @author    Nikhil Malik <nikhil@webkul.com> @ysmnikhil
 * &
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 *
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */


class ProductGrid
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
    * @var Boolean for aliasing
    */

    protected $aliased;


    /**
     * Pagination variable
     * @var String
     */

    protected $perpage;


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
     * array of columns
     * to be filtered
     * @var Array
     */

     protected $filterable;


     /**
     * array of columns
     * to be searched
     *
     * @var Array
     */

     protected $searchable;


     /**
     * mass operations
     *
     * @var Array
     */

     protected $massoperations;


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


     /**
     * Actions $action
     * @var action
     */

    protected $actions;

    /**
     * Attribute Columns
     * for columns which
     * are attributes.
     *
     * @var $attributeColumns
     */

    protected $attributeColumns;

    /**
     * URL parse $parsed
     * @var parse
     */

     protected $parsed;

    //Prepares the input parameters passed as the configuration for datagrid.
    public function make($args)
    {
        // list($name, $select, $table, $join, $columns) = array_values($args);
        $name = $select = $aliased = $table = false;
        $join = $columns = $filterable = $searchable =
        $massoperations = $attributeColumns = $css = $operators = $actions = [];
        extract($args);
        return $this->build($name, $select, $filterable, $searchable, $massoperations, $attributeColumns , $aliased, $perpage, $table, $join, $columns, $css, $operators,$actions);
    }

    //contructor for getting the current locale and channel

    private $locale;
    private $channel;
    private $attributes;
    private $allAttributes;

    public function __construct(AttributeRepository $attributes) {

        $this->channel = request()->get('channel') ?: core()->getCurrentChannelCode();
        $this->locale = request()->get('locale') ?: app()->getLocale();
        $this->attributes = $attributes;

    }

    //This assigns the private and public properties of the datagrid classes from make functions
    public function build(
        $name = null,
        $select = false,
        array $filterable = [],
        array $searchable = [],
        array $massoperations = [],
        array $attributeColumns = [],
        bool $aliased = false,
        $perpage = 0,
        $table = null,
        array $join = [],
        array $columns = null,
        array $css = [],
        array $operators = [],
        array $actions = [],
        Pagination $pagination = null
    ) {
        $this->request = Request::capture();
        $this->setName($name);
        $this->setSelect($select);
        $this->setFilterable($filterable);
        $this->setSearchable($filterable);
        $this->setMassOperations($massoperations);
        $this->setAttributeColumns($attributeColumns);
        $this->setAlias($aliased);
        $this->setPerPage($perpage);
        $this->setTable($table);
        $this->setJoin($join);
        $this->addColumns($columns, true);
        $this->setCss($css);
        $this->setOperators($operators);
        $this->setActions($actions);
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
        $this->select = $select ? : false;
        return $this;
    }

    /**
     * Set Filterable
     * @return $this
     */

    public function setFilterable(array $filterable)
    {
        $this->filterable = $filterable ? : [];
        return $this;
    }

    /**
     * Set Searchable columns
     * @return $this
     */

    public function setSearchable($searchable)
    {
        $this->searchable = $searchable ? : [];
        return $this;
    }

    /**
     * Set mass operations
     * @return $this
     */

    public function setMassOperations($massops)
    {
        $this->massoperations = $massops ? : [];
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
        return $this;
    }

    /**
     * Set the default
     * pagination for
     * data grid.
     *
     * @return $this
     */

    public function setPerPage($perpage)
    {
        $this->perpage = $perpage ? : 5;
        return $this;
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
     * To set the attributes
     * bag on product datagrid.
     *
     * @return $this
     */

    private function setAttributeColumns($attributes = []) {
        $this->attributeColumns = $attributes;
        return $this->attributeColumns;
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
     * Section actions bag
     * here.
     * @return $this
     */

    public function setActions($actions = []) {
        $this->actions = $actions ?: [];
        return $this;
    }

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
     * Currently is not
     * of any use.
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

    /**
     * Used for selecting
     * the columns got in
     * make from controller.
     * @return $this
     */

    private function getSelect()
    {
        $select = [];
        foreach ($this->columns as $column) {
            $select[] = $column->name.' as '.$column->alias;
        }

        $this->query->select(...$select);

        if ($this->select) {
            $this->query->addselect($this->select);
        }

    }

    /**
     * This function will
     * map and resolve the
     * product attributes
     * and thier values
     * per channel and per
     * locale.
     *
     * @return Array
     */

    private function resolveOrMapAttributes () {
        return $this->query->get();
    }


    /**
     * To find the alias
     * of the column and
     * by taking the column
     * name.
     * @return string
     */

    public function findAlias($column_alias) {
        foreach($this->columns as $column) {
            if($column->alias == $column_alias) {
                return $column->name;
            }
        }
    }

    /**
     * Parse the URL
     * and get it ready
     * to be used.
     *
     * @return String
     */

    private function parse()
    {
        $parsed = [];
        $unparsed = url()->full();
        if (count(explode('?', $unparsed))>1) {
            $to_be_parsed = explode('?', $unparsed)[1];
            parse_str($to_be_parsed, $parsed);
            unset($parsed['page']);
            return $parsed;
        } else {
            return $parsed;
        }
    }

    public function getProducts() {
        $qb = DB::table('products')->addSelect('products.*');

        $channel = core()->getCurrentChannelCode();
        $locale = app()->getLocale();

        foreach (['name', 'description', 'short_description', 'price'] as $code) {
        $attribute = $this->attributes->findBy('code', $code);

        $productValueAlias = 'pav_' . $attribute->code;

        $qb->leftJoin('product_attribute_values as ' . $productValueAlias, function($leftJoin) use($channel, $locale, $attribute, $productValueAlias) {

        $leftJoin->on('products.id', $productValueAlias . '.product_id');

        if($attribute->value_per_channel) {
        if($attribute->value_per_locale) {
        $leftJoin->where($productValueAlias . '.channel', $channel)
        ->where($productValueAlias . '.locale', $locale);
        } else {
        $leftJoin->where($productValueAlias . '.channel', $channel);
        }
        } else {
        if($attribute->value_per_locale) {
        $leftJoin->where($productValueAlias . '.locale', $locale);
        }
        }

        $leftJoin->where($productValueAlias . '.attribute_id', $attribute->id);
        });


        $qb->addSelect($productValueAlias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type] . ' as ' . $code);


        if($code == 'name') {
        $filterAlias = 'filter_' . $attribute->code;

        $qb->leftJoin('product_attribute_values as ' . $filterAlias, 'products.id', '=', $filterAlias . '.product_id');
        $qb->where($filterAlias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type], 'Product Name');
        }
        }
    }

    /**
     * ->join('contacts', 'users.id', '=', 'contacts.user_id')
     * @return $this->query
     */

    private function getQueryWithJoin()
    {

        foreach ($this->join as $join) {

            if(array_key_exists('withAttributes',$join)) {

                $qb = $this->query;

                $channel = $this->channel;
                $locale = $this->locale;

                foreach ($this->attributeColumns as $code) {
                    $attribute = $this->attributes->findBy('code', $code);

                    $productValueAlias = 'pav_' . $attribute->code;

                    $qb->leftJoin('product_attribute_values as ' . $productValueAlias, function ($leftJoin) use ($channel, $locale, $attribute, $productValueAlias) {
                        $leftJoin->on('prods.id', $productValueAlias . '.product_id');

                        if ($attribute->value_per_channel) {
                            if ($attribute->value_per_locale) {
                                $leftJoin->where($productValueAlias . '.channel', $channel)->where($productValueAlias . '.locale', $locale);
                            } else {
                                $leftJoin->where($productValueAlias . '.channel', $channel);
                            }
                        } else {
                            if ($attribute->value_per_locale) {
                                $leftJoin->where($productValueAlias . '.locale', $locale);
                            }
                        }

                        $leftJoin->where($productValueAlias . '.attribute_id', $attribute->id);
                    });

                    $qb->addSelect($productValueAlias . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type] . ' as ' . $code);
                }

            }
            else
                $this->query->{$join['join']}($join['table'], $join['primaryKey'], $join['condition'], $join['secondaryKey']);

        }

        $this->query->get();

    }

    /**
     * Use this function
     * when taking filters
     * on columns while
     * fetching columns
     *
     * @return array
     */

    private function getQueryWithColumnFilters()
    {
        foreach ($this->columns as $column) {
            if ($column->filter) { // if the filter bag in array exists then these will be applied.
                dd($column);
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

    /**
     * Function runs when
     * filters, sort, search
     * any of it is applied
     * @return $this->query
     */

    private function getQueryWithFilters()
    {
        $parsed = $this->parse();

        if ($this->aliased) {  //aliasing is expected in this case or it will be changed to presence of join bag
            foreach ($parsed as $key=>$value) {
                if ($key=="sort") {
                    //resolve the case with the column helper class
                    if(substr_count($key,'_') >= 1)
                        $column_name = $this->findAlias($key);

                    //case that don't need any resolving
                    $count_keys = count(array_keys($value));
                    if ($count_keys==1) {
                        $this->query->orderBy(
                            str_replace('_', '.', array_keys($value)[0]),
                            array_values($value)[0]
                        );
                    } else {
                        throw new \Exception('Multiple Sort keys Found, Please Resolve the URL Manually.');
                    }
                } elseif ($key=="search") {

                    $count_keys = count(array_keys($value));
                    if($count_keys==1)
                    $this->query->where(function ($query) use ($parsed) {
                        foreach ($this->searchable as $search) {
                            $query->orWhere($search['column'], 'like', '%'.$parsed['search']['all'].'%');
                        }
                    });
                } else {
                    $column_name = $this->findAlias($key);
                    if (array_keys($value)[0]=="like" || array_keys($value)[0]=="nlike") {
                        foreach ($value as $condition => $filter_value) {
                            $this->query->where(
                                $column_name,
                                $this->operators[$condition],
                                '%'.$filter_value.'%'
                            );
                        }
                    } else {
                        foreach ($value as $condition => $filter_value) {
                            $this->query->where(
                                $column_name,
                                $this->operators[$condition],
                                $filter_value
                            );
                        }
                    }
                }
            }
        } else {
            //this is the case for the non aliasing.
            foreach ($parsed as $key=>$value) {

                if ($key=="sort") {

                    //case that don't need any resolving
                    $count_keys = count(array_keys($value));
                    if ($count_keys==1) {

                        $this->query->orderBy(
                            array_keys($value)[0],
                            array_values($value)[0]
                        );

                    } else {
                        throw new \Exception('Multiple Sort keys Found, Please Resolve the URL Manually.');
                    }
                } elseif ($key=="search") {

                    $count_keys = count(array_keys($value));
                    if($count_keys==1)
                    $this->query->where(function ($query) use ($parsed) {
                        foreach ($this->searchable as $search) {
                            $query->orWhere($search['column'], 'like', '%'.$parsed['search']['all'].'%');
                        }
                    });
                    else
                        throw new \Exception('Multiple Search keys Found, Please Resolve the URL Manually.');

                } else {
                    // $column_name = $key;
                    $column_name = $this->findAlias($key);
                    if (array_keys($value)[0]=="like" || array_keys($value)[0]=="nlike") {
                        foreach ($value as $condition => $filter_value) {
                            $this->query->where(
                                $column_name,
                                $this->operators[$condition],
                                '%'.$filter_value.'%'
                            );
                        }
                    } else {
                        foreach ($value as $condition => $filter_value) {
                            $this->query->where(
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

    private function getDbQueryResults()
    {
        $parsed = $this->parse();

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
                throw new \Exception("Dot(s) cannot be used in table names in Database.");
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

                foreach ($this->join as $index => $join) {

                    $name = strtolower($join['join']);

                    //Allow joins i.e left or right
                    if ($name=='leftjoin' || $name=='rightjoin') {

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
                                        if($index==count($this->join)-1)
                                            $this->getQueryWithJoin();
                                        $alias_proper_secondary = true;
                                    } else {
                                        throw new \Exception('Aliases of Join table and the secondary key columns do not match.');

                                    }
                                } else {
                                    throw new \Exception('Improper aliasing on secondary/join column for join.');
                                }

                            } else {
                                throw new \Exception('Join/Secondary table alias is not found for join.');
                            }
                        } else {
                            throw new \Exception('Primary key and primary table aliases do not match for join.');
                        }
                    } else {
                        $other_joins = true;
                        throw new \Exception('Please check if there is some fault in your aliasing and do not use as in column names or you might have been using a join that is not allowed i.e cross, inner, etc use left and right join only.');
                    }
                }
            }

            //Check for column filter bags and resolve aliasing
            $this->getQueryWithColumnFilters();

            if (!empty($parsed)) {
                $this->getQueryWithFilters();
            }

            $this->results = $this->query->paginate($this->perpage)->appends(request()->input());
            return $this->results;

        } else {

            $this->query = DB::table($this->table);

            $this->getSelect();

            $this->getQueryWithColumnFilters();

            if (!empty($parsed)) {
                $this->getQueryWithFilters();
            }

            $this->results = $this->query->distinct()->paginate($this->perpage)->appends(request()->input());
            return $this->results;
        }
    }

    /**
     * Main Render Function,
     * it renders views responsible
     * for loading datagrid.
     *
     * @return view
     */

    public function render()
    {

        $this->allAttributes = $this->getAttributes();
        $this->getDbQueryResults();
        // dd($this->results);
        return view('ui::datagrid.index', [
            'css' => $this->css,
            'results' => $this->results,
            'columns' => $this->columns,
            'attribute_columns' => $this->attributeColumns,
            'filterable' =>$this->filterable,
            'operators' => $this->operators,
            'massoperations' => $this->massoperations,
            'actions' => $this->actions,
        ]);
    }

    /**
     * Getting all attributes from the repository instance
     * type hinted in the contructor of product grid.
     *
     * @return $this
     */
    public function getAttributes() {
        // dd($this->attributes->all());
        return $this->attributes->all();
    }
}