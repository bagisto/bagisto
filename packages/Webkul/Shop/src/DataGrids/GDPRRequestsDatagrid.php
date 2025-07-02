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
     * Request status "revoked".
     */
    const STATUS_REVOKED = 'revoked';

    /**
     * $status Stores the status of the GDPR request.
     */
    private static $status = '';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('gdpr_data_request as gdpr')
            ->addSelect(
                'gdpr.id',
                'gdpr.status',
                'gdpr.type',
                'gdpr.message',
                'gdpr.created_at',
                'gdpr.updated_at'
            )
            ->where('gdpr.customer_id', auth()->guard('customer')->user()->id);

        return $queryBuilder;
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
            'type'               => 'string',
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
                [
                    'label' => trans('shop::app.customers.account.gdpr.index.datagrid.revoked'),
                    'value' => self::STATUS_REVOKED,
                ],
            ],
            'closure'    => function ($row) {
                self::$status = $row->status;

                switch ($row->status) {
                    case self::STATUS_COMPLETED:
                        return '<p class="label-active">'.trans('shop::app.customers.account.gdpr.index.datagrid.completed').'</p>';

                    case self::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('shop::app.customers.account.gdpr.index.datagrid.pending').'</p>';

                    case self::STATUS_DECLINED:
                        return '<p class="label-canceled">'.trans('shop::app.customers.account.gdpr.index.datagrid.declined').'</p>';

                    case self::STATUS_PROCESSING:
                        return '<p class="label-processing">'.trans('shop::app.customers.account.gdpr.index.datagrid.processing').'</p>';

                    case self::STATUS_REVOKED:
                        return '<span class="label-closed">'.trans('shop::app.customers.account.gdpr.index.datagrid.revoked').'</span>';
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
            'index'           => 'created_at',
            'label'           => trans('shop::app.customers.account.gdpr.index.datagrid.date'),
            'type'            => 'date',
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);

        $this->addColumn([
            'index'      => 'revoke',
            'label'      => trans('shop::app.customers.account.gdpr.index.datagrid.revoke-btn'),
            'type'       => 'string',
            'sortable'   => false,
            'searchable' => false,
            'filterable' => false,
            'closure'    => function ($row) {
                $isPending = self::$status == 'pending';

                $url = route('shop.customers.account.gdpr.revoke', $row->id);

                return $isPending
                    ? '<a href="'.$url.'" class="primary-button rounded-full px-6 py-1.5 max-sm:py-1">'.trans('shop::app.customers.account.gdpr.index.datagrid.revoke-btn').'</a>'
                    : '<button class="primary-button rounded-full px-6 py-1.5 max-sm:py-1" disabled>'.trans('shop::app.customers.account.gdpr.index.datagrid.revoke-btn').'</button>';
            },
        ]);
    }
}
