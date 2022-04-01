<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Webkul\Core\Repositories\ChannelRepository;

class ChannelDataGrid extends DataGrid
{
    /**
     * Assign primary key.
     */
    protected $index = 'id';

    /**
     * Sort order.
     */
    protected $sortOrder = 'desc';

    /**
     * Filter Locale.
     */
    protected $locale;

    /**
     * Create a new datagrid instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @return void
     */
    public function __construct(protected ChannelRepository $channelRepository)
    {
        parent::__construct();

        $this->locale = core()->getRequestedLocaleCode();
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = $this->channelRepository->query()
            ->leftJoin('channel_translations', function($leftJoin) {
                $leftJoin->on('channel_translations.channel_id', '=', 'channels.id')
                    ->where('channel_translations.locale', $this->locale);
            })
            ->addSelect('channels.id', 'channels.code', 'channel_translations.locale', 'channel_translations.name as translated_name', 'channels.hostname');

        $this->addFilter('id', 'channels.id');
        $this->addFilter('code', 'channels.code');
        $this->addFilter('hostname', 'channels.hostname');
        $this->addFilter('translated_name', 'channel_translations.name');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
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
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.channels.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon'         => 'icon trash-icon',
        ]);
    }
}