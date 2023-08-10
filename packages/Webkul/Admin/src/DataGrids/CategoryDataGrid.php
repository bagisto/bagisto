<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Locale;
use Webkul\DataGrid\DataGrid;

class CategoryDataGrid extends DataGrid
{
      /**
     * Index.
     *
     * @var string
     */
    protected $primaryColumn = 'category_id';

    /**
     * Locale.
     *
     * @var string
     */
    protected $locale = 'all';

    /**
     * Contains the keys for which extra filters to show.
     *
     * @var string[]
     */
    protected $extraFilters = [
        'locales',
    ];

    public function __construct()
    {
        $this->locale = core()->getRequestedLocaleCode();
    }

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        if ($this->locale === 'all') {
            $whereInLocales = Locale::query()->pluck('code')->toArray();
        } else {
            $whereInLocales = [$this->locale];
        }

        $queryBuilder = DB::table('categories as cat')
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
            ->groupBy('cat.id', 'ct.locale');

        $this->addFilter('status', 'cat.status');
        $this->addFilter('category_id', 'cat.id');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'category_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
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
            'type'       => 'integer',
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
                if ($value->status) {
                    return '<span class="badge badge-md badge-success">' . trans('admin::app.datagrid.active') . '</span>';
                }

                return '<span class="badge badge-md badge-danger">' . trans('admin::app.datagrid.inactive') . '</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'count',
            'label'      => trans('admin::app.datagrid.no-of-products'),
            'type'       => 'integer',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.catalog.categories.edit',
            'url'    => function ($row) {
                return route('admin.catalog.categories.edit', $row->category_id);
            },
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'DELETE',
            'route'        => 'admin.catalog.categories.delete',
            'confirm_text' => trans('ui::app.datagrid.mass-action.delete', ['resource' => 'product']),
            'url'          => function ($row) {
                return route('admin.catalog.categories.delete', $row->category_id);
            },
        ]);

        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.catalog.categories.mass_delete'),
            'method' => 'POST',
        ]);

        $this->addMassAction([
            'type'    => 'update',
            'label'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('admin.catalog.categories.mass_update'),
            'method'  => 'POST',
            'options' => [
                trans('admin::app.datagrid.active')    => 1,
                trans('admin::app.datagrid.inactive')  => 0,
            ],
        ]);
    }
}
