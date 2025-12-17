<?php

namespace Webkul\Shop\DataGrids\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\RMA\Repositories\RMAStatusRepository;
use Webkul\Sales\Models\Order;

class RMADataGrid extends DataGrid
{
    /**
     * Constructor for the class.
     *
     * @return void
     */
    public function __construct(
        protected RMAStatusRepository $rmaStatusRepository,
    ) {}

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('rma')
            ->join('orders', 'orders.id', '=', 'rma.order_id')
            ->join('rma_items', 'rma_items.rma_id', '=', 'rma.id')
            ->leftJoin('rma_statuses', 'rma_statuses.id', '=', 'rma.rma_status_id')
            ->select(
                'rma.id',
                'rma.order_id',
                'rma.created_at',
                'orders.customer_email',
                'orders.status as order_status',
                'rma_statuses.title',
                'rma_statuses.color as rma_status_color',
                DB::raw('SUM('.$tablePrefix.'rma_items.quantity) as total_quantity'),
            )
            ->where('orders.customer_id', auth()->guard('customer')->user()->id)
            ->groupBy('rma.id');

        $this->addFilter('id', 'rma.id');
        $this->addFilter('order_id', 'rma.order_id');
        $this->addFilter('rma_status_title', 'rma_statuses.title');
        $this->addFilter('customer_email', 'orders.customer_email');
        $this->addFilter('created_at', 'rma.created_at');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('shop::app.customers.account.rma.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('shop::app.customers.account.rma.index.datagrid.order-ref'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return '<span class="text-sm text-blue-500"><a href="'.route('shop.customers.account.orders.view', ['id' => $row->order_id]).'">'.'#'.$row->order_id.'</a></span>';
            },
        ]);

        $this->addColumn([
            'index'              => 'title',
            'label'              => trans('shop::app.customers.account.rma.index.datagrid.rma-status'),
            'type'               => 'string',
            'filterable_type'    => 'dropdown',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_options' => $this->rmaStatusRepository->all(['title as label', 'title as value'])->toArray(),
            'closure'            => function ($row) {
                // if (
                //     $row->order_status == Order::STATUS_CANCELED
                //     && $row->order_status == Order::STATUS_CLOSED
                // ) {
                //     return '<p class="label-canceled">'.trans('shop::app.rma.status.status-name.item-canceled').'</p>';
                // }

                $color = $row->rma_status_color ?? '';

                return '<p class="label-active" style="background:'.$color.';">'.$row->title.'</p>';
            },
        ]);

        $this->addColumn([
            'index'      => 'total_quantity',
            'label'      => trans('shop::app.rma.guest.create.quantity'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('shop::app.customers.account.rma.index.datagrid.create'),
            'type'            => 'date',
            'sortable'        => true,
            'searchable'      => true,
            'filterable'      => true,
            'filterable_type' => 'date_range',
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        $this->addAction([
            'title'  => trans('shop::app.rma.customer-rma-index.view'),
            'icon'   => 'icon-eye',
            'method' => 'GET',
            'url'    => function ($row) {
                return route('shop.customers.account.rma.view', $row->id);
            },
        ]);

        $this->addAction([
            'title'     => trans('shop::app.rma.customer-rma-index.view'),
            'icon'      => 'icon-cancel',
            'method'    => 'GET',
            'url'      => function ($row) {
                return route('shop.customers.account.rma.cancel', $row->id);
            },
        ]);
    }
}
