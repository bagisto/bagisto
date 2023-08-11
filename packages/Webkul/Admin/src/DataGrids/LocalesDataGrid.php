<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class LocalesDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('locales')->addSelect('id', 'code', 'name', 'direction');

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
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'direction',
            'label'      => trans('admin::app.datagrid.direction'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->direction == 'ltr') {
                    return trans('admin::app.datagrid.ltr');
                }

                return trans('admin::app.datagrid.rtl');
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon pencil-lg-icon',
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.exchange_rates.edit',
            'url'    => function ($row) {
                return route('admin.locales.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'         => 'icon trash-icon',
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'DELETE',
            'route'        => 'admin.exchange_rates.delete',
            'url'          => function ($row) {
                return route('admin.locales.delete', $row->id);
            },
        ]);
    }
}
