<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class SliderDataGrid extends DataGrid
{
    protected $index = 'slider_id';

    protected $sortOrder = 'desc';

    protected $locale = 'all';

    protected $channel = 'all';

    public function __construct()
    {
        parent::__construct();

        $this->locale = request()->get('locale') ?? 'all';
        $this->channel = request()->get('channel') ?? 'all';
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('sliders as sl')
          ->addSelect('sl.id as slider_id', 'sl.title', 'sl.locale', 'ch.name', 'ch.code')
          ->leftJoin('channels as ch', 'sl.channel_id', '=', 'ch.id');

        if ($this->locale !== 'all') {
            $queryBuilder->where('locale', $this->locale);
        }

        if ($this->channel !== 'all') {
            $queryBuilder->where('ch.code', $this->channel);
        }

        $this->addFilter('slider_id', 'sl.id');
        $this->addFilter('channel_name', 'ch.name');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'slider_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.channel-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'locale',
            'label' => trans('admin::app.datagrid.locale'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.sliders.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.sliders.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }
}