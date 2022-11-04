<?php

namespace Webkul\Ui\DataGrid;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Webkul\Ui\DataGrid\Traits\ProvideBouncer;
use Webkul\Ui\DataGrid\Traits\ProvideCollection;
use Webkul\Ui\DataGrid\Traits\ProvideDataGridPlus;
use Webkul\Ui\DataGrid\Traits\ProvideExceptionHandler;

abstract class DataGrid
{
    use ProvideBouncer, ProvideCollection, ProvideDataGridPlus, ProvideExceptionHandler;

    /**
     * Set index columns, ex: id.
     *
     * @var string
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

    /**
     * Create datagrid instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->invoker = $this;
    }

    /**
     * Abstract method.
     */
    abstract public function prepareQueryBuilder();

    /**
     * Abstract method.
     */
    abstract public function addColumns();

    /**
     * Add the index as alias of the column and use the column to make things happen.
     *
     * @param string  $alias
     * @param string  $column
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
     * @param  array  $column
     * @return void
     */
    public function addColumn($column)
    {
        $this->checkRequiredColumnKeys($column);

        $this->fireEvent('add.column.before.' . $column['index']);

        $this->columns[] = $column;

        $this->setCompleteColumnDetails($column);

        $this->fireEvent('add.column.after.' . $column['index']);
    }

    /**
     * Set complete column details.
     *
     * @param  array  $column
     * @return void
     */
    public function setCompleteColumnDetails($column)
    {
        $this->completeColumnDetails[] = $column;
    }

    /**
     * Set query builder.
     *
     * @param  \Illuminate\Database\Query\Builder  $queryBuilder
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
     * @param  array  $action
     * @param  bool   $specialPermission
     * @return void
     */
    public function addAction($action, $specialPermission = false)
    {
        $this->checkRequiredActionKeys($action);

        $this->checkPermissions($action, $specialPermission, function ($action, $eventName) {
            $this->fireEvent('action.before.' . $eventName);

            $action['key'] = Str::slug($action['title'], '_', app()->getLocale());

            $this->actions[] = $action;
            $this->enableAction = true;

            $this->fireEvent('action.after.' . $eventName);
        });
    }

    /**
     * Add mass action. Some datagrids are used in shops also. So extra
     * parameters is their. If needs to give an access just pass true
     * in second param.
     *
     * @param  array  $massAction
     * @param  bool   $specialPermission
     * @return void
     */
    public function addMassAction($massAction, $specialPermission = false)
    {
        $massAction['route'] = $this->getRouteNameFromUrl($massAction['action'], $massAction['method']);

        $this->checkPermissions($massAction, $specialPermission, function ($action, $eventName) {
            $this->fireEvent('mass.action.before.' . $eventName);

            $this->massActions[] = $action;
            $this->enableMassAction = true;

            $this->fireEvent('mass.action.after.' . $eventName);
        }, 'label');
    }

    /**
     * Prepare mass actions.
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

        return view('ui::datagrid.table')->with('results', $this->prepareViewData());
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
     * Prepare view data.
     *
     * @return array
     */
    public function prepareViewData()
    {
        return [
            'index'             => $this->index,
            'records'           => $this->getCollection(),
            'columns'           => $this->completeColumnDetails,
            'actions'           => $this->actions,
            'enableActions'     => $this->enableAction,
            'massactions'       => $this->massActions,
            'enableMassActions' => $this->enableMassAction,
            'paginated'         => $this->paginate,
            'itemsPerPage'      => $this->itemsPerPage,
            'norecords'         => __('ui::app.datagrid.no-records'),
            'extraFilters'      => $this->getNecessaryExtraFilters(),
        ];
    }

    /**
     * Trigger event.
     *
     * @param  string  $name
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
     * Get necessary extra details.
     *
     * @return array
     */
    protected function getNecessaryExtraFilters()
    {
        $necessaryExtraFilters = [];

        $checks = [
            'channels'        => core()->getAllChannels(),
            'locales'         => core()->getAllLocales(),
            'customer_groups' => core()->getAllCustomerGroups(),
        ];

        foreach ($checks as $key => $val) {
            if (in_array($key, $this->extraFilters)) {
                $necessaryExtraFilters[$key] = $val;
            }
        }

        return $necessaryExtraFilters;
    }

    /**
     * Get column details by name. You can minimize the details by
     * setting the key also.
     *
     * @param  string  $columnName
     * @param  string  $key
     * @return array
     */
    protected function getColumnByName($columnName, $key = null)
    {
        $column = collect($this->columns)
            ->filter(fn($column) => $column['index'] === $columnName)
            ->first();

        if ($key && isset($column[$key])) {
            return $column[$key];
        }

        return $column;
    }
}
