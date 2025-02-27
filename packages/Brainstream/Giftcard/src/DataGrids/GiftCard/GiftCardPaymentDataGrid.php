<?php

namespace Brainstream\Giftcard\DataGrids\GiftCard;

use Illuminate\Support\Facades\DB;
use Brainstream\DataGrid\DataGrid;

class GiftCardPaymentDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('gift_card_payments')
            ->addSelect([
                'id',
                'giftcard_number',
                'order_id',
                'payment_id',
                'payer_id',
                'payer_email',
                'amount',
                'currency',
                'status',
                'payment_data',
                'payment_type',
                'created_at',
                'updated_at'
            ]);

        return $queryBuilder;
    }

    /**
     * Add Columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('giftcard::app.giftcard.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'giftcard_number',
            'label'      => trans('giftcard::app.giftcard.datagrid.giftcard_number'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'order_id',
            'label'      => trans('giftcard::app.giftcard.datagrid.order_id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'payment_id',
            'label'      => trans('giftcard::app.giftcard.datagrid.payment_id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'payer_id',
            'label'      => trans('giftcard::app.giftcard.datagrid.payer_id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'payer_email',
            'label'      => trans('giftcard::app.giftcard.datagrid.payer_email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'amount',
            'label'      => trans('giftcard::app.giftcard.datagrid.amount'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'currency',
            'label'      => trans('giftcard::app.giftcard.datagrid.currency'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('giftcard::app.giftcard.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'payment_type',
            'label'      => trans('giftcard::app.giftcard.datagrid.payment_type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }
}
