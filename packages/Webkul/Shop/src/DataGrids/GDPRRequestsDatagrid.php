<?php

namespace Webkul\Shop\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class GDPRRequestsDatagrid extends DataGrid
{
    /**
     * Request status "approved".
     */
    const STATUS_COMPLETED = 'completed';

    /**
     * Request status "pending", indicating awaiting approval.
     */
    const STATUS_PENDING = 'pending';

    /**
     * Request status "declined", indicating rejection or denial.
     */
    const STATUS_DECLINED = 'declined';

    /**
     * Request status "processing".
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
            ->addSelect(
                'gdpr.id',
                'gdpr.status',
                'gdpr.type',
                'gdpr.message',
                'gdpr.created_at',
                'gdpr.updated_at'
            )
            ->where('gdpr.customer_id', auth()->guard('customer')->user()->id);
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('shop::app.customers.account.gdpr.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('shop::app.customers.account.gdpr.index.datagrid.status'),
            'type'               => 'integer',
            'searchable'         => true,
            'sortable'           => false,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('shop::app.customers.account.gdpr.index.datagrid.pending'),
                    'value' => self::STATUS_PENDING,
                ],
                [
                    'label' => trans('shop::app.customers.account.gdpr.index.datagrid.processing'),
                    'value' => self::STATUS_PROCESSING,
                ],
                [
                    'label' => trans('shop::app.customers.account.gdpr.index.datagrid.completed'),
                    'value' => self::STATUS_COMPLETED,
                ],
                [
                    'label' => trans('shop::app.customers.account.gdpr.index.datagrid.declined'),
                    'value' => self::STATUS_DECLINED,
                ],
            ],
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('shop::app.customers.account.gdpr.index.datagrid.completed').'</p>';

                    case self::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('shop::app.customers.account.gdpr.index.datagrid.pending').'</p>';

                    case self::STATUS_DECLINED:
                        return '<p class="label-canceled">'.trans('shop::app.customers.account.gdpr.index.datagrid.declined').'</p>';

                    case self::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('shop::app.customers.account.gdpr.index.datagrid.processing').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'              => 'type',
            'label'              => trans('shop::app.customers.account.gdpr.index.datagrid.type'),
            'type'               => 'string',
            'sortable'           => false,
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('shop::app.customers.account.gdpr.index.datagrid.delete'),
                    'value' => 'delete',
                ],
                [
                    'label' => trans('shop::app.customers.account.gdpr.index.datagrid.update'),
                    'value' => 'update',
                ],
            ],
            'closure'    => function ($row) {
                switch ($row->type) {
                    case 'delete':
                        return trans('shop::app.customers.account.gdpr.index.datagrid.delete');

                    case 'update':
                        return trans('shop::app.customers.account.gdpr.index.datagrid.update');
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'message',
            'label'      => trans('shop::app.customers.account.gdpr.index.datagrid.message'),
            'type'       => 'string',
            'sortable'   => false,
            'searchable' => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('shop::app.customers.account.gdpr.index.datagrid.date'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);
    }
}
