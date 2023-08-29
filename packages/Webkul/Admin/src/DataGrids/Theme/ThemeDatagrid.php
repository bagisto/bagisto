<?php

namespace Webkul\Admin\DataGrids\Theme;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ThemeDatagrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('theme_customizations')
            ->addSelect(
                'theme_customizations.id',
                'theme_customizations.type',
                'theme_customizations.name',
                'theme_customizations.sort_order',
                'theme_customizations.status',
            );

        $this->addFilter('type', 'channel_translations.type');
        $this->addFilter('name', 'channel_translations.name');
        $this->addFilter('sort_order', 'channel_translations.sort_order');
        $this->addFilter('status', 'theme_customizations.status');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.settings.channels.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('Type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('Name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => trans('Sort Order'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('Status'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return '<p class="label-processing">' . trans('Active') . '</p>';
                }

                return '<p class="label-pending">' . trans('Inactive') . '</p>';
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-view',
            'title'  => trans('admin::app.sales.orders.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return '#';
            },
        ]);
    }
}
