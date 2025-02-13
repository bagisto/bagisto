<?php

namespace Webkul\Admin\DataGrids\Customers;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class GDPRDataGrid extends DataGrid
{
    /**
     * GDPR status "approved".
     */
    const STATUS_COMPLETED = 'completed';

    /**
     * GDPR status "pending", indicating awaiting approval.
     */
    const STATUS_PENDING = 'pending';

    /**
     * GDPR status "declined", indicating rejection or denial.
     */
    const STATUS_DECLINED = 'declined';

    /**
     * GDPR status "processing".
     */
    const STATUS_PROCESSING = 'processing';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        return DB::table('gdpr_data_request as gdpr')
            ->leftJoin('customers', 'gdpr.customer_id', '=', 'customers.id')
            ->addSelect(
                'gdpr.id',
                DB::raw("CONCAT(customers.first_name, ' ', customers.last_name) as customer_name"),
                'gdpr.customer_id',
                'gdpr.status',
                'gdpr.type',
                'gdpr.message',
                'gdpr.created_at'
            );
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.customers.gdpr.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'customer_name',
            'label'      => trans('admin::app.customers.gdpr.index.datagrid.customer-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.customers.gdpr.index.datagrid.status'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('admin::app.customers.gdpr.index.datagrid.completed').'</p>';

                    case self::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('admin::app.customers.gdpr.index.datagrid.pending').'</p>';

                    case self::STATUS_DECLINED:
                        return '<p class="label-canceled">'.trans('admin::app.customers.gdpr.index.datagrid.declined').'</p>';

                    case self::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('admin::app.customers.gdpr.index.datagrid.processing').'</p>';
                }
            },

        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('admin::app.customers.gdpr.index.datagrid.type'),
            'type'       => 'string',
            'sortable'   => false,
            'searchable' => true,
            'filterable' => false,

        ]);

        $this->addColumn([
            'index'      => 'message',
            'label'      => trans('admin::app.customers.gdpr.index.datagrid.message'),
            'type'       => 'string',
            'sortable'   => false,
            'searchable' => true,
            'filterable' => false,

        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.customers.gdpr.index.datagrid.created-at'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        if (bouncer()->hasPermission('customers.groups.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.customers.gdpr.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.customers.gdpr.edit');
                },
            ]);
        }

        if (bouncer()->hasPermission('catalog.categories.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.customers.gdpr.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.customers.gdpr.delete', $row->id);
                },
            ]);
        }
    }
}
