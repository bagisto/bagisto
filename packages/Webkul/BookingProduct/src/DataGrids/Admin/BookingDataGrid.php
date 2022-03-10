<?php

namespace Webkul\BookingProduct\DataGrids\Admin;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Webkul\Ui\DataGrid\DataGrid;

class BookingDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $index = 'order_id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $dbPrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('bookings')
            ->leftJoin('orders', 'bookings.order_id', '=', 'orders.id')
            ->select('bookings.id as id', 'orders.increment_id as order_id', 'bookings.from as from', 'bookings.to as to', 'bookings.qty as qty', 'orders.created_at as created_at');

        $this->addFilter('id', 'bookings.id');
        $this->addFilter('order_id', 'orders.increment_id');
        $this->addFilter('qty', 'bookings.qty');
        $this->addFilter('created_at', 'orders.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.datagrid.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'qty',
            'label'      => trans('admin::app.datagrid.qty'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'from',
            'label'      => trans('bookingproduct::app.admin.datagrid.from'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($value) {
                return Carbon::createFromTimestamp($value->from)->format('d F, Y H:iA');
            }
        ]);

        $this->addColumn([
            'index'      => 'to',
            'label'      => trans('bookingproduct::app.admin.datagrid.to'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($value) {
                return Carbon::createFromTimestamp($value->to)->format('d F, Y H:iA');
            }
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.datagrid.created-date'),
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
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
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'admin.sales.orders.view',
            'icon'   => 'icon eye-icon',
        ]);
    }
}
