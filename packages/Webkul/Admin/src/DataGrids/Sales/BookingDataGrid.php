<?php

namespace Webkul\Admin\DataGrids\Sales;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class BookingDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('bookings')
            ->leftJoin('orders', 'bookings.order_id', '=', 'orders.id')
            ->select('bookings.id as id', 'orders.increment_id as order_id', 'bookings.from as from', 'bookings.to as to', 'bookings.qty as qty', 'orders.created_at as created_at');

        $this->addFilter('id', 'bookings.id');
        $this->addFilter('order_id', 'orders.increment_id');
        $this->addFilter('qty', 'bookings.qty');
        $this->addFilter('created_at', 'orders.created_at');

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
            'label'      => trans('admin::app.sales.booking.index.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.sales.booking.index.datagrid.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'qty',
            'label'      => trans('admin::app.sales.booking.index.datagrid.qty'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'           => 'from',
            'label'           => trans('admin::app.sales.booking.index.datagrid.from'),
            'type'            => 'datetime',
            'searchable'      => true,
            'sortable'        => true,
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
            'closure'         => function ($value) {
                return Carbon::createFromTimestamp($value->from)->format('d M, Y H:iA');
            },
        ]);

        $this->addColumn([
            'index'           => 'to',
            'label'           => trans('admin::app.sales.booking.index.datagrid.to'),
            'type'            => 'datetime',
            'searchable'      => true,
            'sortable'        => true,
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
            'closure'         => function ($value) {
                return Carbon::createFromTimestamp($value->to)->format('d M, Y H:iA');
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.sales.booking.index.datagrid.created-date'),
            'type'            => 'datetime',
            'searchable'      => true,
            'sortable'        => true,
            'filterable'      => true,
            'filterable_type' => 'datetime_range',
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
            'icon'   => 'icon-view',
            'title'  => trans('admin::app.sales.booking.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.sales.orders.view', $row->order_id);
            },
        ]);
    }
}
