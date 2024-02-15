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
        $whereInLocales = core()->getRequestedLocaleCode() === 'all'
            ? core()->getAllLocales()->pluck('code')->toArray()
            : [core()->getRequestedLocaleCode()];

        $queryBuilder = DB::table('theme_customizations')
            ->distinct()
            ->join('theme_customization_translations as tct', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('theme_customizations.id', '=', 'tct.theme_customization_id')
                    ->whereIn('tct.locale', $whereInLocales);
            })
            ->join('channel_translations', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('theme_customizations.channel_id', '=', 'channel_translations.channel_id')
                    ->whereIn('channel_translations.locale', $whereInLocales);
            })
            ->addSelect(
                'theme_customizations.id',
                'theme_customizations.type',
                'theme_customizations.sort_order',
                'channel_translations.name as channel_name',
                'theme_customizations.status',
                'theme_customizations.name as name',
            );

        $this->addFilter('type', 'theme_customizations.type');
        $this->addFilter('name', 'theme_customizations.name');
        $this->addFilter('sort_order', 'theme_customizations.sort_order');
        $this->addFilter('status', 'theme_customizations.status');
        $this->addFilter('channel_name', 'channel_name');

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
            'index'      => 'channel_name',
            'label'      => trans('admin::app.settings.themes.index.datagrid.channel_name'),
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
                    return '<p class="label-active">'.trans('admin::app.settings.themes.index.datagrid.active').'</p>';
                }

                return '<p class="label-pending">'.trans('admin::app.settings.themes.index.datagrid.inactive').'</p>';
            },
        ]);
    }

    public function prepareActions()
    {
        if (bouncer()->hasPermission('settings.themes.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.settings.themes.index.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.settings.themes.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('settings.themes.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.settings.themes.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.settings.themes.delete', $row->id);
                },
            ]);
        }
    }
}
