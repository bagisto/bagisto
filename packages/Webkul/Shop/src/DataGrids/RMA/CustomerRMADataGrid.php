<?php

namespace Webkul\Shop\DataGrids\RMA;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\RMA\Repositories\RMAStatusRepository;

class CustomerRMADataGrid extends DataGrid
{
    /**
     * Pending status.
     * 
     * @var string
     */
    public const PENDING = 'Pending';

    /**
     * Closed status.
     * 
     * @var string
     */
    public const CLOSED = 'closed';

    /**
     * Canceled status.
     * 
     * @var string
     */
    public const CANCELED = 'canceled';

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
        $customerId = null;

        $guestEmail = session('guestEmail');

        if (auth()->guard('customer')->check()) {
            session()->forget(['guestOrderId', 'guestEmail']);

            $customerId = auth()->guard('customer')->id();
        }

        $orderId = session()->get('guestOrderId') ?? null;

        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('rma')
            ->join('orders', 'orders.id', '=', 'rma.order_id')
            ->join('rma_items', 'rma_items.rma_id', '=', 'rma.id')
            ->select(
                'rma.id',
                'rma.status',
                'rma.order_id',
                'rma.request_status',
                'rma.request_status as rmaStatus',
                'rma.created_at',
                'orders.customer_email',
                'orders.status as order_status',
                DB::raw('SUM('.$tablePrefix.'rma_items.quantity) as total_quantity'),
            )
            ->groupBy('rma.id');

        $queryBuilder->where(function ($query) use ($orderId, $customerId, $guestEmail) {
            if (! is_null($orderId)) {
                $query->where('orders.id', $orderId)
                    ->where('orders.customer_email', $guestEmail);
            } elseif ($guestEmail) {
                $query->where('orders.customer_email', $guestEmail)
                    ->where('orders.is_guest', 1);
            } elseif ($customerId) {
                $query->where('orders.customer_id', $customerId);
            }
        });

        $this->addFilter('id', 'rma.id');
        $this->addFilter('order_id', 'rma.order_id');
        $this->addFilter('request_status', 'rma.request_status');
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
                return '<span class="text-sm text-blue-500"><a href="'.route('shop.customers.account.orders.view', ['id' => $row->order_id]).'">'.'#'.$row->order_id.'</a></span>';
            },
        ]);

        $this->addColumn([
            'index'              => 'request_status',
            'label'              => trans('admin::app.sales.rma.all-rma.index.datagrid.rma-status'),
            'type'               => 'string',
            'filterable_type'    => 'dropdown',
            'searchable'         => true,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_options' => $this->rmaStatusRepository->all(['title as label', 'title as value'])->toArray(),
            'closure'            => function ($row) {
                $rmaStatusData = app('Webkul\RMA\Repositories\RMAStatusRepository')
                    ->where('title', $row->request_status)
                    ->first();

                if (
                    $row->order_status == self::CANCELED
                    && $row->order_status == self::CLOSED
                ) {
                    return '<p class="label-canceled">'.trans('shop::app.rma.status.status-name.item-canceled').'</p>';
                }

                return '<p class="label-active" style="background:'.$rmaStatusData?->color.';">'.$row->request_status.'</p>';
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
        $route = 'shop.guest.account.rma.view';

        $cancelRoute = 'shop.rma.action.cancel';

        if (
            auth()->guard('customer')->user()
            && request()->route()->getName() == 'shop.customers.account.rma.index'
        ) {
            $route = 'shop.customers.account.rma.view';

            $cancelRoute = 'shop.rma.action.cancel';
        }

        $this->addAction([
            'title'  => trans('shop::app.rma.customer-rma-index.view'),
            'icon'   => 'icon-eye',
            'method' => 'GET',
            'url'    => function ($row) use ($route) {
                return route($route, $row->id);
            },
        ]);

        $this->addAction([
            'title'     => trans('shop::app.rma.customer-rma-index.view'),
            'icon'      => 'icon-cancel',
            'method'    => 'GET',
            'condition' => function ($row) {
                if (
                    $row->rmaStatus != 'solved'
                ) {
                    return false;
                }

                return true;
            },
            'url'      => function ($row) use ($cancelRoute) {
                return route($cancelRoute, $row->id);
            },
        ]);
    }
}
