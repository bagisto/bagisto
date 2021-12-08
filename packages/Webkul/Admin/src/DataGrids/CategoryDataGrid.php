<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Category\Models\Category;
use Webkul\Core\Models\Locale;
use Webkul\Ui\DataGrid\DataGrid;

class CategoryDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected string $index = 'category_id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected string $sortOrder = 'desc';

    /**
     * Locale.
     *
     * @var string
     */
    protected string $locale = 'all';

    /**
     * Create a new data grid instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->locale = core()->getRequestedLocaleCode();
    }

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder(): void
    {
        if ($this->locale === 'all') {
            $whereInLocales = Locale::query()->pluck('code')->toArray();
        } else {
            $whereInLocales = [$this->locale];
        }

        $queryBuilder = Category::from('categories as cat')
            ->select(
                'cat.id as category_id',
                'ct.name',
                'cat.position',
                'cat.status',
                'ct.locale',
                DB::raw('COUNT(DISTINCT ' . DB::getTablePrefix() . 'pc.product_id) as count')
            )
            ->leftJoin('category_translations as ct', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('cat.id', '=', 'ct.category_id')
                    ->whereIn('ct.locale', $whereInLocales);
            })
            ->leftJoin('product_categories as pc', 'cat.id', '=', 'pc.category_id')
            ->groupBy('cat.id', 'ct.locale',);


        $this->addFilter('status', 'cat.status');
        $this->addFilter('category_id', 'cat.id');

        $this->setQueryBuilder($queryBuilder);
    }

	/**
	 * Add columns.
	 *
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 * @return void
	 */
	public function addColumns(): void
    {
        $this->addColumn([
            'index'      => 'category_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'position',
            'label'      => trans('admin::app.datagrid.position'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status == 1) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'count',
            'label'      => trans('admin::app.datagrid.no-of-products'),
            'type'       => 'number',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
        ]);
    }

	/**
	 * Prepare actions.
	 *
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 * @return void
	 */
	public function prepareActions(): void
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.catalog.categories.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.catalog.categories.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon'         => 'icon trash-icon',
            'function'     => 'deleteCategory(event, "delete")'
        ]);

        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.catalog.categories.massdelete'),
            'method' => 'POST',
        ]);
    }
}
