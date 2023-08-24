<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class NewsLetterDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('subscribers_list')
            ->select(
                'subscribers_list.id',
                'subscribers_list.is_subscribed as status',
                'subscribers_list.email'
            );

        $this->addFilter('status', 'subscribers_list.is_subscribed');

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
            'label'      => trans('admin::app.marketing.email-marketing.newsletters.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.marketing.email-marketing.newsletters.index.datagrid.subscribed'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->status) {
                    return trans('admin::app.marketing.email-marketing.newsletters.index.datagrid.true');
                }

                return trans('admin::app.marketing.email-marketing.newsletters.index.datagrid.false');
            },
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('admin::app.marketing.email-marketing.newsletters.index.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
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
            'title'  => trans('admin::app.marketing.email-marketing.newsletters.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.customers.subscribers.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'         => 'icon-delete',
            'title'        => trans('admin::app.marketing.email-marketing.newsletters.index.datagrid.delete'),
            'method'       => 'DELETE',
            'url'          => function ($row) {
                return route('admin.customers.subscribers.delete', $row->id);
            },
        ]);
    }
}
