<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CustomerDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected $primaryColumn = 'customer_id';

    /**
     * Items per page.
     *
     * @var int
     */
    protected $itemsPerPage = 10;

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customers')
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

        // $this->addFilter('customer_id', 'customers.id');
        // $this->addFilter('full_name', DB::raw('CONCAT(' . DB::getTablePrefix() . 'customers.first_name, " ", ' . DB::getTablePrefix() . 'customers.last_name)'));
        // $this->addFilter('group', 'customer_groups.name');
        // $this->addFilter('phone', 'customers.phone');
        // $this->addFilter('gender', 'customers.gender');
        // $this->addFilter('status', 'status');
        // $this->addFilter('is_suspended', 'customers.is_suspended');

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
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'full_name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('admin::app.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'group',
            'label'      => trans('admin::app.datagrid.group'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'phone',
            'label'      => trans('admin::app.datagrid.phone'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($row) {
                if (! $row->phone) {
                    return '-';
                }

                return $row->phone;
            },
        ]);

        $this->addColumn([
            'index'      => 'gender',
            'label'      => trans('admin::app.datagrid.gender'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($row) {
                if (! $row->gender) {
                    return '-';
                }

                return $row->gender;
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                $html = '';

                if ($row->status) {
                    $html .= '<span class="badge badge-md badge-success">' . trans('admin::app.customers.customers.active') . '</span>';
                } else {
                    $html .= '<span class="badge badge-md badge-danger">' . trans('admin::app.customers.customers.inactive') . '</span>';
                }

                if ($row->is_suspended) {
                    $html .= '<span class="badge badge-md badge-danger">' . trans('admin::app.datagrid.suspended') . '</span>';
                }

                return $html;
            },
        ]);

        $this->addColumn([
            'index'       => 'is_suspended',
            'label'       => trans('admin::app.datagrid.suspended'),
            'type'        => 'boolean',
            'searchable'  => false,
            'sortable'    => true,
            'filterable'  => true,
            'visibility'  => false,
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
            'method' => 'GET',
            'route'  => 'admin.customer.edit',
            'title'  => trans('admin::app.datagrid.edit'),
            'url'          => function ($row) {
                return route('admin.customer.edit', $row->customer_id);
            },
        ]);

        $this->addAction([
            'method' => 'GET',
            'route'  => 'admin.customer.view',
            'title'  => trans('admin::app.datagrid.view'),
            'url'    => function ($row) {
                return route('admin.customer.view', $row->customer_id);
            },
        ]);

        $this->addAction([
            'method' => 'GET',
            'route'  => 'admin.customer.login_as_customer',
            'target' => 'blank',
            'title'  => trans('admin::app.datagrid.login-as-customer'),
            'url'    => function ($row) {
                return route('admin.customer.login_as_customer', $row->customer_id);
            },
        ]);

        $this->addAction([
            'method' => 'DELETE',
            'route'  => 'admin.customer.delete',
            'title'  => trans('admin::app.datagrid.delete'),
            'url'    => function ($row) {
                return route('admin.customer.delete', $row->customer_id);
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
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.customer.mass_delete'),
            'method' => 'POST',
        ]);

        $this->addMassAction([
            'type'    => 'update',
            'label'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('admin.customer.mass_update'),
            'method'  => 'POST',
            'options' => [
                trans('admin::app.datagrid.active')    => 1,
                trans('admin::app.datagrid.inactive')  => 0,
            ],
        ]);
    }
}
