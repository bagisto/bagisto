<?php

namespace Brainstream\Giftcard\DataGrids\GiftCard;

use Illuminate\Support\Facades\DB;
use Brainstream\DataGrid\DataGrid;

class GiftCardBalanceDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('gift_card_balances')
            ->addSelect([
                'id',
                'giftcard_number',
                'giftcard_amount',
                'used_giftcard_amount',
                'remaining_giftcard_amount',
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
            'index'      => 'giftcard_amount',
            'label'      => trans('giftcard::app.giftcard.datagrid.giftcard_amount'),
            'type'       => 'decimal',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'used_giftcard_amount',
            'label'      => trans('giftcard::app.giftcard.datagrid.used_giftcard_amount'),
            'type'       => 'decimal',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'remaining_giftcard_amount',
            'label'      => trans('giftcard::app.giftcard.datagrid.remaining_giftcard_amount'),
            'type'       => 'decimal',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }
}
