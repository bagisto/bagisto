<?php

namespace Webkul\Admin\DataGrids\Marketing;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class EmailTemplateDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketing_templates')->addSelect('id', 'name', 'status');

        $this->addFilter('status', 'status');

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
            'label'      => trans('admin::app.marketing.email-marketing.templates.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.marketing.email-marketing.templates.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.marketing.email-marketing.templates.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status == 'active') {
                    return trans('admin::app.marketing.email-marketing.templates.index.datagrid.active');
                } elseif ($value->status == 'inactive') {
                    return trans('admin::app.marketing.email-marketing.templates.index.datagrid.inactive');
                } elseif ($value->status == 'draft') {
                    return trans('admin::app.marketing.email-marketing.templates.index.datagrid.draft');
                }
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
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.marketing.email-marketing.templates.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.marketing.promotions.email_templates.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'    => 'icon-delete',
            'title'   => trans('admin::app.marketing.email-marketing.templates.index.datagrid.delete'),
            'method'  => 'POST',
            'url'     => function ($row) {
                return route('admin.marketing.promotions.email_templates.delete', $row->id);
            },
        ]);
    }
}
