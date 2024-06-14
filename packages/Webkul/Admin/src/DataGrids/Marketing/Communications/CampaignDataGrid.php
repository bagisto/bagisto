<?php

namespace Webkul\Admin\DataGrids\Marketing\Communications;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CampaignDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketing_campaigns')
            ->select(
                'id',
                'name',
                'subject',
                'status'
            );

        $this->addFilter('status', 'marketing_campaigns.status');

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
            'label'      => trans('admin::app.marketing.communications.campaigns.index.datagrid.id'),
            'type'       => 'integer',
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.marketing.communications.campaigns.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'subject',
            'label'      => trans('admin::app.marketing.communications.campaigns.index.datagrid.subject'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.marketing.communications.campaigns.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return trans('admin::app.marketing.communications.campaigns.index.datagrid.active');
                }

                return trans('admin::app.marketing.communications.campaigns.index.datagrid.inactive');
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
        if (bouncer()->hasPermission('marketing.communications.campaigns.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.marketing.communications.campaigns.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.marketing.communications.campaigns.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketing.communications.campaigns.delete')) {
            $this->addAction([
                'icon'         => 'icon-delete',
                'title'        => trans('admin::app.marketing.communications.campaigns.index.datagrid.delete'),
                'method'       => 'DELETE',
                'url'          => function ($row) {
                    return route('admin.marketing.communications.campaigns.delete', $row->id);
                },
            ]);
        }
    }
}
