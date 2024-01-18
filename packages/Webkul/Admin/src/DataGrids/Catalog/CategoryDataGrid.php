<?php

namespace Webkul\Admin\DataGrids\Catalog;

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
     * Contains the keys for which extra filters to show.
     *
     * @var string[]
     */
    protected $extraFilters = [
        'locales',
    ];

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        if (core()->getRequestedLocaleCode() === 'all') {
            $whereInLocales = Locale::query()->pluck('code')->toArray();
        } else {
            $whereInLocales = [core()->getRequestedLocaleCode()];
        }

        $queryBuilder = DB::table('categories as cat')
            ->select(
                'cat.id as category_id',
                'ct.name',
                'cat.position',
                'cat.status',
                'ct.locale',
                DB::raw('COUNT(DISTINCT '.DB::getTablePrefix().'pc.product_id) as count')
            )
            ->leftJoin('category_translations as ct', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('cat.id', '=', 'ct.category_id')
                    ->whereIn('ct.locale', $whereInLocales);
            })
            ->leftJoin('product_categories as pc', 'cat.id', '=', 'pc.category_id')
            ->groupBy('cat.id', 'ct.locale');

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
            'label'      => trans('admin::app.catalog.categories.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.catalog.categories.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'position',
            'label'      => trans('admin::app.catalog.categories.index.datagrid.position'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.catalog.categories.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return '<span class="badge badge-md badge-success">'.trans('admin::app.catalog.categories.index.datagrid.active').'</span>';
                }

                return '<span class="badge badge-md badge-danger">'.trans('admin::app.catalog.categories.index.datagrid.inactive').'</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'count',
            'label'      => trans('admin::app.catalog.categories.index.datagrid.no-of-products'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('catalog.categories.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.catalog.categories.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.catalog.categories.edit', $row->category_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('catalog.categories.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.catalog.categories.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.catalog.categories.delete', $row->category_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('catalog.categories.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.catalog.categories.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.catalog.categories.mass_delete'),
            ]);
        }

        if (bouncer()->hasPermission('catalog.categories.edit')) {
            $this->addMassAction([
                'title'   => trans('admin::app.catalog.categories.index.datagrid.update-status'),
                'method'  => 'POST',
                'url'     => route('admin.catalog.categories.mass_update'),
                'options' => [
                    [
                        'label' => trans('admin::app.catalog.categories.index.datagrid.active'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('admin::app.catalog.categories.index.datagrid.inactive'),
                        'value' => 0,
                    ],
                ],
            ]);
        }
    }
}
