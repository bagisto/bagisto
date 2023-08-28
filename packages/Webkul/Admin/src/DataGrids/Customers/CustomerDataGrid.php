<?php

namespace Webkul\Admin\DataGrids\Customers;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
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
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customers')
            ->leftJoin('addresses', function ($join) {
                $join->on('customers.id', '=', 'addresses.customer_id')
                    ->where('addresses.address_type', '=', 'customer');
            })
            ->addSelect('customers.id as customer_id')
            ->addSelect(DB::raw('COUNT(DISTINCT addresses.id) as address_count'))
            ->groupBy('customers.id')

            ->leftJoin('orders', function ($join) {
                $join->on('customers.id', '=', 'orders.customer_id');
            })
            ->addSelect('customers.id as customer_id')
            ->addSelect(DB::raw('COUNT(DISTINCT orders.id) as order_count'))

            ->groupBy('customers.id')

            ->leftJoin('customer_groups', 'customers.customer_group_id', '=', 'customer_groups.id')
            ->addSelect(
                'customers.id as customer_id',
                'customers.email',
                'customers.phone',
                'customers.gender',
                'customers.status',
                'customers.is_suspended',
                'customer_groups.name as group',
            )
            ->addSelect(
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name) as full_name')
            );

        $this->addFilter('customer_id', 'customers.id');
        $this->addFilter('email', 'customers.email');
        $this->addFilter('full_name', DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name)'));
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
            'index'      => 'customer_id',
            'label'      => trans('admin::app.customers.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('admin::app.customers.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('admin::app.customers.index.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('admin::app.customers.index.datagrid.phone'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.customers.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'gender',
            'label'      => trans('admin::app.customers.index.datagrid.gender'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'group',
            'label'      => trans('admin::app.customers.index.datagrid.group'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'       => 'is_suspended',
            'label'       => trans('admin::app.customers.index.datagrid.suspended'),
            'type'        => 'boolean',
            'searchable'  => false,
            'filterable'  => true,
            'visibility'  => false,
            'sortable'    => true,
        ]);

        $this->addColumn([
            'index'       => 'total_base_grand_total',
            'label'       => trans('admin::app.customers.index.datagrid.revenue'),
            'type'        => 'integer',
            'searchable'  => false,
            'filterable'  => false,
            'sortable'    => false,
            'closure'     => function($row) {
                return app(OrderRepository::class)->scopeQuery(function($q) use($row) {
                    return $q->whereNotIn('status', ['canceled', 'closed'])
                        ->where('customer_id', $row->customer_id);
                })->sum('grand_total');
            }
        ]);

        $this->addColumn([
            'index'       => 'order_count',
            'label'       => trans('admin::app.customers.index.datagrid.order-count'),
            'type'        => 'integer',
            'searchable'  => false,
            'filterable'  => false,
            'sortable'    => true,
        ]);

        $this->addColumn([
            'index'       => 'address_count',
            'label'       => trans('admin::app.customers.index.datagrid.address-count'),
            'type'        => 'integer',
            'searchable'  => false,
            'filterable'  => false,
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
            'title'  => trans('admin::app.customers.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.customers.customers.view', $row->customer_id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-exit',
            'title'  => trans('admin::app.customers.index.datagrid.login-as-customer'),
            'method' => 'GET',
            'target' => 'blank',
            'url'    => function ($row) {
                return route('admin.customers.customers.login_as_customer', $row->customer_id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.customers.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.customers.customers.delete', $row->customer_id);
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
        $this->addMassAction([
            'title'  => trans('admin::app.customers.index.datagrid.delete'),
            'method' => 'POST',
            'url'    => route('admin.customers.customers.mass_delete'),
        ]);

        $this->addMassAction([
            'title'   => trans('admin::app.customers.index.datagrid.update-status'),
            'method'  => 'POST',
            'url'     => route('admin.customers.customers.mass_update'),
            'options' => [
                [
                    'name' => trans('admin::app.customers.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'name' => trans('admin::app.customers.index.datagrid.inactive'),
                    'value' => 0,
                ],
            ],
        ]);
    }
}
