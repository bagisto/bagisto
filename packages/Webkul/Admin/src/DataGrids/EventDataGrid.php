<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Marketing\Models\Template;
use Webkul\Ui\DataGrid\DataGrid;

class EventDataGrid extends DataGrid
{
    protected string $index = 'id';

    protected string $sortOrder = 'desc';

    public function prepareQueryBuilder(): void
    {
        $queryBuilder = Template::query()->select('id', 'name', 'date');

        $this->setQueryBuilder($queryBuilder);
    }

	/**
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 */
	public function addColumns(): void
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
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'date',
            'label'      => trans('admin::app.datagrid.date'),
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

	/**
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 */
	public function prepareActions(): void
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.events.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.events.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Event']),
            'icon'         => 'icon trash-icon',
        ]);
    }
}
