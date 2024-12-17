<?php

namespace Webkul\Admin\DataGrids\Theme;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ThemeDataGrid extends DataGrid
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
            ->join('theme_customization_translations', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('theme_customizations.id', '=', 'theme_customization_translations.theme_customization_id')
                    ->whereIn('theme_customization_translations.locale', $whereInLocales);
            })
            ->join('channel_translations', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('theme_customizations.channel_id', '=', 'channel_translations.channel_id')
                    ->whereIn('channel_translations.locale', $whereInLocales);
            })
            ->select(
                'theme_customizations.id',
                'theme_customizations.type',
                'theme_customizations.sort_order',
                'channel_translations.name as channel_name',
                'theme_customizations.status',
                'theme_customizations.name as name',
                'theme_customizations.theme_code',
                'theme_customizations.channel_id'
            );

        $this->addFilter('id', 'theme_customizations.id');
        $this->addFilter('type', 'theme_customizations.type');
        $this->addFilter('name', 'theme_customizations.name');
        $this->addFilter('sort_order', 'theme_customizations.sort_order');
        $this->addFilter('status', 'theme_customizations.status');
        $this->addFilter('channel_name', 'theme_customizations.channel_id');
        $this->addFilter('theme_code', 'theme_customizations.theme_code');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $themes = config('themes.shop');

        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.settings.themes.index.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'channel_name',
            'label'              => trans('admin::app.settings.themes.index.datagrid.channel_name'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => core()->getAllChannels()
                ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                ->values()
                ->toArray(),
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'theme_code',
            'label'              => trans('admin::app.settings.themes.index.datagrid.theme'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => collect($themes = config('themes.shop'))
                ->map(fn ($theme, $code) => ['label' => $theme['name'], 'value' => $code])
                ->values()
                ->toArray(),
            'closure'=> function ($row) use ($themes) {
                return collect($themes)->first(fn ($theme, $code) => $code === $row->theme_code)['name'] ?? 'N/A';
            },
            'sortable'           => true,
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
            'index'              => 'status',
            'label'              => trans('admin::app.settings.themes.index.datagrid.status'),
            'type'               => 'boolean',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_options' => [
                [
                    'label' => trans('admin::app.settings.themes.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'label' => trans('admin::app.settings.themes.index.datagrid.inactive'),
                    'value' => 0,
                ],
            ],
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
