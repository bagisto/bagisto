<?php

namespace Webkul\Admin\DataGrids\Sales;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class EUWithdrawalDataGrid extends DataGrid
{
    /**
     * Build the base query — withdrawal record joined to its order and channel.
     */
    public function prepareQueryBuilder(): Builder
    {
        $query = DB::table('eu_withdrawals as w')
            ->leftJoin('orders as o', 'w.order_id', '=', 'o.id')
            ->leftJoin('channels as c', 'w.channel_id', '=', 'c.id')
            ->select(
                'w.id',
                'w.uuid',
                'w.order_id',
                'w.customer_email',
                'w.status',
                'w.received_at',
                'w.confirmation_sent_at',
                'w.confirmation_error',
                'o.increment_id as order_increment_id',
                'c.code as channel_code',
            );

        $this->addFilter('order_increment_id', 'o.increment_id');
        $this->addFilter('channel_code', 'c.code');

        return $query;
    }

    /**
     * Declare the columns shown in the grid.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index' => 'received_at',
            'label' => trans('admin::app.eu_withdrawal.datagrid.received_at'),
            'type' => 'datetime',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'uuid',
            'label' => trans('admin::app.eu_withdrawal.datagrid.uuid'),
            'type' => 'string',
            'searchable' => true,
        ]);

        $this->addColumn([
            'index' => 'order_increment_id',
            'label' => trans('admin::app.eu_withdrawal.datagrid.order'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'customer_email',
            'label' => trans('admin::app.eu_withdrawal.datagrid.customer_email'),
            'type' => 'string',
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'channel_code',
            'label' => trans('admin::app.eu_withdrawal.datagrid.channel'),
            'type' => 'string',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.eu_withdrawal.datagrid.status'),
            'type' => 'string',
            'filterable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'confirmation_sent_at',
            'label' => trans('admin::app.eu_withdrawal.datagrid.confirmation_sent_at'),
            'type' => 'datetime',
            'sortable' => true,
        ]);
    }

    /**
     * Register the per-row actions. Currently a single View link, gated by
     * the sales.eu_withdrawals.view ACL permission.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('sales.eu_withdrawals.view')) {
            $this->addAction([
                'index' => 'view',
                'icon' => 'icon-view',
                'title' => trans('admin::app.eu_withdrawal.datagrid.view'),
                'method' => 'GET',
                'url' => fn ($row) => route('admin.sales.eu-withdrawals.view', $row->id),
            ]);
        }
    }
}
