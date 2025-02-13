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
            'label'      => trans('shop::app.customers.account.gdpr.id'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('shop::app.customers.account.gdpr.status'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_COMPLETED:
                        return '<span class="label-active">'.trans('shop::app.customers.account.gdpr.completed').'</span>';
                    case self::STATUS_PENDING:
                        return '<span class="label-pending">'.trans('shop::app.customers.account.gdpr.pending').'</span>';
                    case self::STATUS_DECLINED:
                        return '<span class="label-cancelled">'.trans('shop::app.customers.account.gdpr.declined').'</span>';
                    case self::STATUS_PROCESSING:
                        return '<span class="label-processing">'.trans('shop::app.customers.account.gdpr.processing').'</span>';
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('shop::app.customers.account.gdpr.type'),
            'type'       => 'string',
            'sortable'   => false,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'message',
            'label'      => trans('shop::app.customers.account.gdpr.message'),
            'type'       => 'string',
            'sortable'   => false,
            'searchable' => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('shop::app.customers.account.gdpr.date'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);
    }
}
