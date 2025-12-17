<?php

namespace Webkul\Admin\DataGrids\Sales\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\RMA\Repositories\RMAStatusRepository;
use Webkul\Sales\Models\Order;

class RMADataGrid extends DataGrid
{
    /**
     * Constructor.
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
        $table_prefix = DB::getTablePrefix();

        $queryBuilder = DB::table('rma')
            ->leftJoin('orders', 'orders.id', '=', 'rma.order_id')
            ->leftJoin('rma_statuses', 'rma_statuses.id', '=', 'rma.rma_status_id')
            ->addSelect(
                'rma.id',
                'rma.order_id',
                'orders.is_guest as is_guest',
                DB::raw('CONCAT('.$table_prefix.'orders.customer_first_name, " ", '.$table_prefix.'orders.customer_last_name) as customer_name'),
                'rma_statuses.title',
                'rma.order_status as rma_order_status',
                'rma.created_at',
                'orders.status as order_status',
                'rma_statuses.color as rma_status_color',
            )
            ->whereIn('order_id', DB::table('orders')->pluck('id')?->toArray());

        $this->addFilter('id', 'rma.id');
        $this->addFilter('order_id', 'rma.order_id');
        $this->addFilter('title', 'rma_statuses.title');
        $this->addFilter('rma_order_status', 'rma.order_status');
        $this->addFilter('created_at', 'rma.created_at');
        $this->addFilter('customer_name', DB::raw('CONCAT('.$table_prefix.'orders.customer_first_name, " ", '.$table_prefix.'orders.customer_last_name)'));

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.sales.rma.all-rma.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('admin::app.sales.rma.all-rma.index.datagrid.order-ref'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return '<a href="'.route('admin.sales.orders.view', ['id' => $row->order_id]).'">'."<span class='text-blue-600'>#".$row->order_id.'</span></a>';
            },
        ]);

        $this->addColumn([
            'index'      => 'customer_name',
            'label'      => trans('admin::app.sales.rma.all-rma.index.datagrid.customer-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if (! empty($row->is_guest)) {
                    return '<span>'.$row->customer_name.' ('.trans('admin::app.sales.rma.all-rma.index.datagrid.guest').')'.'</span>';
                }

                return $row->customer_name;
            },
        ]);

        $this->addColumn([
            'index'              => 'title',
            'label'              => trans('admin::app.sales.rma.all-rma.index.datagrid.rma-status'),
            'type'               => 'string',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => $this->rmaStatusRepository->all(['title as label', 'title as value'])->toArray(),
            'closure'            => function ($row) {
                if (
                    $row->order_status == Order::STATUS_CANCELED
                    && $row->order_status == Order::STATUS_CLOSED
                ) {
                    return '<p class="label-canceled">'.trans('admin::app.sales.rma.all-rma.index.datagrid.item-canceled').'</p>';
                }

                $color = $row->rma_status_color ?? '';

                return '<p class="label-active" style="background:'.$color.';">'.$row->title.'</p>';
            },
        ]);

        $this->addColumn([
            'index'              => 'rma_order_status',
            'label'              => trans('admin::app.sales.rma.all-rma.index.datagrid.order-status'),
            'type'               => 'string',
            'filterable_type'    => 'dropdown',
            'searchable'         => true,
            'filterable'         => true,
            'sortable'           => true,
            'filterable_options' => [
                [
                    'label' => trans('admin::app.sales.rma.all-rma.index.datagrid.delivered'),
                    'value' => 1,
                ], [
                    'label' => trans('admin::app.sales.rma.all-rma.index.datagrid.undelivered'),
                    'value' => 0,
                ],
            ],
            'closure'            => function ($row) {
                if (
                    $row->order_status == Order::STATUS_CANCELED
                    || $row->order_status == Order::STATUS_CLOSED
                ) {
                    return '<p class="label-'.$row->order_status.'">'.trans('admin::app.sales.rma.all-rma.index.datagrid.'.$row->order_status).'</p>';
                } elseif ($row->rma_order_status) {
                    return '<p class="label-active">'.trans('admin::app.sales.rma.all-rma.index.datagrid.delivered').'</p>';
                }

                return '<p class="label-info">'.trans('admin::app.sales.rma.all-rma.index.datagrid.undelivered').'</p>';
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.sales.rma.all-rma.index.datagrid.create'),
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
        if (bouncer()->hasPermission('sales.rma.create')) {
            $this->addAction([
                'title'  => trans('admin::app.sales.rma.all-rma.index.datagrid.view'),
                'icon'   => 'icon-view',
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.rma.view', $row->id);
                },
            ]);
        }
    }
}
