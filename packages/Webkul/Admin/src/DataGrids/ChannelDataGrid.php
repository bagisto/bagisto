<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Core\Repositories\ChannelRepository;
use Webkul\DataGrid\DataGrid;

class ChannelDataGrid extends DataGrid
{
    /**
     * Filter Locale.
     */
    protected $locale;

    public function __construct(protected ChannelRepository $channelRepository)
    {
        $this->locale = core()->getRequestedLocaleCode();
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = $this->channelRepository->query()
            ->leftJoin('channel_translations', function ($leftJoin) {
                $leftJoin->on('channel_translations.channel_id', '=', 'channels.id')
                    ->where('channel_translations.locale', $this->locale);
            })
            ->addSelect('channels.id', 'channels.code', 'channel_translations.locale', 'channel_translations.name as translated_name', 'channels.hostname');

        // $this->addFilter('id', 'channels.id');
        // $this->addFilter('code', 'channels.code');
        // $this->addFilter('hostname', 'channels.hostname');
        // $this->addFilter('translated_name', 'channel_translations.name');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'translated_name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'hostname',
            'label'      => trans('admin::app.datagrid.hostname'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.channels.edit',
            'url'    => function ($row) {
                dd($row);
                return route('admin.channels.edit', $row->id);
            },
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.channels.delete',
            'confirm_text' => trans('ui::app.datagrid.mass-action.delete', ['resource' => 'product']),
            'url'          => function ($row) {
                return route('admin.channels.delete', $row->id);
            },
        ]);
    }
}
