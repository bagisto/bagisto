<?php

namespace Webkul\Admin\DataGrids\Customers;

use Illuminate\Support\Facades\DB;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;

class CustomerDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $primaryColumn = 'customer_id';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CustomerGroupRepository $customerGroupRepository) {}

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('customers')
            ->leftJoin('addresses', function ($join) {
                $join->on('customers.id', '=', 'addresses.customer_id')
                    ->where('addresses.address_type', '=', 'customer');
            })
            ->leftJoin('orders', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('customer_groups', 'customers.customer_group_id', '=', 'customer_groups.id')
            ->addSelect(
                'customers.id as customer_id',
                'customers.email',
                'customers.phone',
                'customers.gender',
                'customers.status',
                'customers.is_suspended',
                'customer_groups.name as group',
                'customers.channel_id',
            )
            ->addSelect(DB::raw('COUNT(DISTINCT '.$tablePrefix.'addresses.id) as address_count'))
            ->addSelect(DB::raw('COUNT(DISTINCT '.$tablePrefix.'orders.id) as order_count'))
            ->addSelect(DB::raw('CONCAT('.$tablePrefix.'customers.first_name, " ", '.$tablePrefix.'customers.last_name) as full_name'))
            ->groupBy('customers.id');

        $this->addFilter('channel_id', 'customers.channel_id');
        $this->addFilter('customer_id', 'customers.id');
        $this->addFilter('email', 'customers.email');
        $this->addFilter('full_name', DB::raw('CONCAT('.$tablePrefix.'customers.first_name, " ", '.$tablePrefix.'customers.last_name)'));
        $this->addFilter('group', 'customer_groups.name');
        $this->addFilter('phone', 'customers.phone');
        $this->addFilter('status', 'customers.status');

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
            'index'              => 'channel_id',
            'label'              => trans('admin::app.customers.customers.index.datagrid.channel'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => collect(core()->getAllChannels())
                ->map(fn ($channel) => ['label' => $channel->name, 'value' => $channel->id])
                ->values()
                ->toArray(),
            'sortable'   => true,
            'visibility' => false,
        ]);

        $this->addColumn([
            'index'      => 'customer_id',
            'label'      => trans('admin::app.customers.customers.index.datagrid.id'),
            'type'       => 'integer',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('admin::app.customers.customers.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('admin::app.customers.customers.index.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('admin::app.customers.customers.index.datagrid.phone'),
            'type'       => 'integer',
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.customers.customers.index.datagrid.status'),
            'type'       => 'boolean',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'gender',
            'label'      => trans('admin::app.customers.customers.index.datagrid.gender'),
            'type'       => 'string',
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'group',
            'label'              => trans('admin::app.customers.customers.index.datagrid.group'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => $this->customerGroupRepository->all(['name as label', 'name as value'])->toArray(),
        ]);

        $this->addColumn([
            'index'    => 'is_suspended',
            'label'    => trans('admin::app.customers.customers.index.datagrid.suspended'),
            'type'     => 'boolean',
            'sortable' => true,
        ]);

        $this->addColumn([
            'index'       => 'revenue',
            'label'       => trans('admin::app.customers.customers.index.datagrid.revenue'),
            'type'        => 'integer',
            'closure'     => function ($row) {
                return app(OrderRepository::class)->scopeQuery(function ($q) use ($row) {
                    return $q->whereNotIn('status', [Order::STATUS_CANCELED, Order::STATUS_CLOSED])
                        ->where('customer_id', $row->customer_id);
                })->sum('base_grand_total_invoiced');
            },
        ]);

        $this->addColumn([
            'index'       => 'order_count',
            'label'       => trans('admin::app.customers.customers.index.datagrid.order-count'),
            'type'        => 'integer',
            'sortable'    => true,
        ]);

        $this->addColumn([
            'index'       => 'address_count',
            'label'       => trans('admin::app.customers.customers.index.datagrid.address-count'),
            'type'        => 'integer',
            'sortable'    => true,
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
            'title'  => trans('admin::app.customers.customers.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.customers.customers.view', $row->customer_id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-exit',
            'title'  => trans('admin::app.customers.customers.index.datagrid.login-as-customer'),
            'method' => 'GET',
            'target' => 'blank',
            'url'    => function ($row) {
                return route('admin.customers.customers.login_as_customer', $row->customer_id);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('customers.customers.delete')) {
            $this->addMassAction([
                'title'  => trans('admin::app.customers.customers.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.customers.customers.mass_delete'),
            ]);
        }

        if (bouncer()->hasPermission('customers.customers.edit')) {
            $this->addMassAction([
                'title'   => trans('admin::app.customers.customers.index.datagrid.update-status'),
                'method'  => 'POST',
                'url'     => route('admin.customers.customers.mass_update'),
                'options' => [
                    [
                        'label' => trans('admin::app.customers.customers.index.datagrid.active'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('admin::app.customers.customers.index.datagrid.inactive'),
                        'value' => 0,
                    ],
                ],
            ]);
        }
    }
}
