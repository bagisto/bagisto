<?php

namespace Webkul\Admin\DataGrids\Settings;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ChannelDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('channels')
            ->leftJoin('channel_translations', function ($leftJoin) {
                $leftJoin->on('channel_translations.channel_id', '=', 'channels.id')
                    ->where('channel_translations.locale', core()->getRequestedLocaleCode());
            })
            ->select(
                'channels.id',
                'channels.code',
                'channel_translations.locale',
                'channel_translations.name as translated_name',
                'channels.hostname'
            );

        $this->addFilter('id', 'channels.id');
        $this->addFilter('code', 'channels.code');
        $this->addFilter('hostname', 'channels.hostname');
        $this->addFilter('translated_name', 'channel_translations.name');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.settings.channels.index.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.settings.channels.index.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'translated_name',
            'label'      => trans('admin::app.settings.channels.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'hostname',
            'label'      => trans('admin::app.settings.channels.index.datagrid.host-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    public function prepareActions()
    {
        if (bouncer()->hasPermission('settings.channels.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.settings.channels.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.settings.channels.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('settings.channels.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.settings.channels.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.settings.channels.delete', $row->id);
                },
            ]);
        }
    }
}
