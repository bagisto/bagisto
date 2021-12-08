<?php

namespace Webkul\Ui\DataGrid;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Webkul\Ui\DataGrid\Traits\ProvideBouncer;
use Webkul\Ui\DataGrid\Traits\ProvideCollection;
use Webkul\Ui\DataGrid\Traits\ProvideExceptionHandler;

abstract class DataGrid
{
    use ProvideBouncer, ProvideCollection, ProvideExceptionHandler;

    /**
     * Set index columns, ex: id.
     *
     * @var string
     */
    protected string $index;

    /**
     * Default sort order of data grid.
     *
     * @var string
     */
    protected string $sortOrder = 'asc';

    /**
     * Situation handling property when working with custom columns in data grid, helps abstaining
     * aliases on custom column.
     *
     * @var bool
     */
    protected bool $enableFilterMap = false;

    /**
     * This is array where aliases and custom column's name are passed.
     *
     * @var string[]
     */
    protected array $filterMap = [];

    /**
     * Array to hold all the columns which will be displayed on frontend.
     *
     * @var string[]
     */
    protected array $columns = [];


    /**
     * Complete column details.
     *
     * @var array
     */
    protected array $completeColumnDetails = [];

	/**
	 * Hold query builder instance of the query prepared by executing datagrid
	 * class method `setQueryBuilder`.
	 *
	 * @var \Illuminate\Database\Eloquent\Builder
	 */
	protected Builder $queryBuilder;

	/**
	 * Final result of the data grid program that is length aware paginator object.
	 *
	 * @var \Illuminate\Pagination\LengthAwarePaginator
	 */
	protected LengthAwarePaginator $collection;

	/**
     * Set of handy click tools which you can use for various operations.
     * ex: dynamic and static redirects, deleting, etc.
     *
     * @var array
     */
    protected array $actions = [];

    /**
     * Works on selection of values index column as comma separated list as response
     * to your endpoint set as route.
     *
     * @var array
     */
    protected array $massActions = [];

    /**
     * Parsed value of the url parameters.
     *
     * @var array
     */
    protected array $parse;

    /**
     * To show mass action or not.
     *
     * @var bool
     */
    protected bool $enableMassAction = false;

    /**
     * To enable actions or not.
     */
    protected bool $enableAction = false;

    /**
     * Paginate the collection or not.
     *
     * @var bool
     */
    protected bool $paginate = true;

    /**
     * If paginated then is the amount of items per each page.
     *
     * @var int|mixed
     */
    protected $itemsPerPage = 10;

    /**
     * Operators mapping.
     *
     * @var array
     */
    protected array $operators = [
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
    protected array $bindings = [
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
    protected array $selectcomponents = [
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
    protected array $extraFilters = [];

    /**
     * The current admin user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|null|object
     */
    protected $currentUser;

	/**
	 * @var \Webkul\Ui\DataGrid\DataGrid
	 */
	private DataGrid $invoker;

	/**
	 * Create DataGrid instance.
	 *
	 * @see \Webkul\Ui\DataGrid\Facades\DataGrid
	 * @return void
	 */
	public function __construct()
    {
        $this->invoker = $this;

        $this->currentUser = auth()->guard('admin')->user();
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
     * @param string $alias
     * @param string $column
     * @return void
     */
    public function addFilter(string $alias, string $column): void
	{
        $this->filterMap[$alias] = $column;

        $this->enableFilterMap = true;
    }

	/**
	 * Add column.
	 *
	 * @see \Webkul\Ui\DataGrid\Traits\ProvideExceptionHandler::checkRequiredColumnKeys
	 * @param array $column
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 * @return void
	 */
	public function addColumn(array $column): void
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
	 * @param array $column
	 * @return void
	 */
	public function setCompleteColumnDetails(array $column): void
	{
        $this->completeColumnDetails[] = $column;
    }

	/**
	 * Set query builder.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $queryBuilder
	 * @return void
	 */
	public function setQueryBuilder(Builder $queryBuilder): void
	{
        $this->queryBuilder = $queryBuilder;
    }

	/**
	 * Add action. Some data-grids are used in shops as well. So extra
	 * parameters is their. If needs to give an access just pass true
	 * in second param.
	 *
	 * @param array $action
	 * @param bool  $specialPermission
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 * @return void
	 */
	public function addAction(array $action, bool $specialPermission = false): void
	{
        $this->checkRequiredActionKeys($action);

        $this->checkPermissions($action, $specialPermission, function ($action, $eventName) {
            $this->fireEvent('action.before.' . $eventName);

			$action['key']      = Str::slug($action['title'], '_');

			$this->actions[]    = $action;
			$this->enableAction = true;

            $this->fireEvent('action.after.' . $eventName);
        });
    }

	/**
	 * Add mass action. Some datagrids are used in shops also. So extra
	 * parameters is their. If needs to give an access just pass true
	 * in second param.
	 *
	 * @param array $massAction
	 * @param bool  $specialPermission
	 * @return void
	 */
	public function addMassAction(array $massAction, bool $specialPermission = false): void
	{
        $massAction['route'] = $this->getRouteNameFromUrl($massAction['action'], $massAction['method']);

        $this->checkPermissions($massAction, $specialPermission, function ($action, $eventName) {
            $this->fireEvent('mass.action.before.' . $eventName);

			$this->massActions[]    = $action;
			$this->enableMassAction = true;

            $this->fireEvent('mass.action.after.' . $eventName);
        }, 'label');
    }

	/**
	 * Preprare mass actions.
	 *
	 * @return void
	 */
	public function prepareMassActions(): void
	{
	}

	/**
	 * Prepare actions.
	 *
	 * @return void
	 */
	public function prepareActions(): void
	{
	}

	/**
	 * Render view.
	 *
	 * @see \Webkul\Ui\DataGrid\Traits\ProvideQueryResolver
	 * @see \Webkul\Ui\DataGrid\Traits\ProvideCollection
	 * @throws \Exception
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
	 * @see \Webkul\Ui\DataGrid\Traits\*
	 * @see \Webkul\Ui\DataGrid\Traits\ProvideCollection
	 * @throws \Exception
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function export(): LengthAwarePaginator
	{
		$this->paginate = false;

        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return $this->getResults();
    }

	/**
	 * Prepare view data.
	 *
	 * @see \Webkul\Ui\DataGrid\Traits\*
	 * @see \Webkul\Ui\DataGrid\Traits\ProvideCollection
	 * @throws \Exception on get results failure
	 * @return array
	 */
	public function prepareViewData(): array
	{
        return [
            'index'             => $this->index,
            'records'           => $this->getResults(),
            'columns'           => $this->completeColumnDetails,
            'actions'           => $this->actions,
            'enableActions'     => $this->enableAction,
            'massactions'       => $this->massActions,
            'enableMassActions' => $this->enableMassAction,
            'paginated'         => $this->paginate,
            'itemsPerPage'      => $this->itemsPerPage,
            'norecords'         => __('ui::app.datagrid.no-records'),
            'extraFilters'      => $this->getNecessaryExtraFilters()
        ];
    }

	/**
	 * Trigger event.
	 *
	 * @param ?string $name
	 * @return void
	 */
	protected function fireEvent(?string $name): void
	{
		if (empty($name) === false) {
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
	protected function getNecessaryExtraFilters(): array
	{
		$necessaryExtraFilters = [];

        $checks = [
            'channels'        => core()->getAllChannels(),
            'locales'         => core()->getAllLocales(),
            'customer_groups' => core()->getAllCustomerGroups()
        ];

        foreach ($checks as $key => $val) {
            if (in_array($key, $this->extraFilters)) {
                $necessaryExtraFilters[$key] = $val;
            }
        }

        return $necessaryExtraFilters;
    }
}
