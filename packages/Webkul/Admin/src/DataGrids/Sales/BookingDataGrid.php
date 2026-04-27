<?php

namespace Webkul\Admin\DataGrids\Sales;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class BookingDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('bookings')
            ->leftJoin('orders', 'bookings.order_id', '=', 'orders.id')
            ->select(
                'orders.increment_id as order_id',
                'orders.created_at as created_at',
                'bookings.id as id',
                'bookings.from as from',
                'bookings.to as to',
                'bookings.qty as qty'
            );

        $this->addFilter('id', 'bookings.id');
        $this->addFilter('order_id', 'orders.increment_id');
        $this->addFilter('qty', 'bookings.qty');
        $this->addFilter('from', DB::raw('FROM_UNIXTIME(`'.$tablePrefix.'bookings`.`from`)'));
        $this->addFilter('to', DB::raw('FROM_UNIXTIME(`'.$tablePrefix.'bookings`.`to`)'));
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
            'index' => 'id',
            'label' => trans('admin::app.sales.booking.index.datagrid.id'),
            'type' => 'integer',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'order_id',
            'label' => trans('admin::app.sales.booking.index.datagrid.order-id'),
            'type' => 'integer',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'qty',
            'label' => trans('admin::app.sales.booking.index.datagrid.qty'),
            'type' => 'integer',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index' => 'from',
            'label' => trans('admin::app.sales.booking.index.datagrid.from'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'closure' => function ($value) {
                return Carbon::createFromTimestamp($value->from)
                    ->timezone(config('app.timezone'))
                    ->format('d M, Y H:iA');
            },
        ]);

        $this->addColumn([
            'index' => 'to',
            'label' => trans('admin::app.sales.booking.index.datagrid.to'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'filterable_type' => 'datetime_range',
            'closure' => function ($value) {
                return Carbon::createFromTimestamp($value->to)
                    ->timezone(config('app.timezone'))
                    ->format('d M, Y H:iA');
            },
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('admin::app.sales.booking.index.datagrid.created-date'),
            'type' => 'datetime',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
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
            'icon' => 'icon-view',
            'title' => trans('admin::app.sales.booking.index.datagrid.view'),
            'method' => 'GET',
            'url' => function ($row) {
                return route('admin.sales.orders.view', $row->order_id);
            },
        ]);
    }
}
