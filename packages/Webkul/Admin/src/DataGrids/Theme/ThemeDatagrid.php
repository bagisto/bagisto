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
            'label'      => trans('admin::app.settings.themes.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('admin::app.settings.themes.index.datagrid.type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.settings.themes.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => trans('admin::app.settings.themes.index.datagrid.sort-order'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.settings.themes.index.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return '<p class="label-processing">' . trans('admin::app.settings.themes.index.datagrid.active') . '</p>';
                }

                return '<p class="label-pending">' . trans('admin::app.settings.themes.index.datagrid.inactive') . '</p>';
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.settings.themes.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.theme.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'    => 'icon-delete',
            'title'   => trans('admin::app.settings.themes.index.datagrid.delete'),
            'method'  => 'DELETE',
            'url'     => function ($row) {
                return route('admin.theme.delete', $row->id);
            },
        ]);
    }
}
