<?php

namespace Brainstream\Giftcard\DataGrids\GiftCard;

use Illuminate\Support\Facades\DB;
use Brainstream\DataGrid\DataGrid;

class GiftCardDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('gift_cards')
            ->addSelect([
                'id',
                'giftcard_number',
                'giftcard_amount',
                'giftcard_status',
                'creationdate',
                'expirationdate',
                'expirein',
                'sendername',
                'senderemail',
                'recipientname',
                'recipientemail'
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
            'closure'    => function ($value) {
                return $value->giftcard_number ?? '-';
            },
        ]);

        $this->addColumn([
            'index'      => 'giftcard_amount',
            'label'      => trans('giftcard::app.giftcard.datagrid.giftcard_amount'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'giftcard_status',
            'label'      => trans('giftcard::app.giftcard.datagrid.giftcard_status'),
            'type'       => 'boolean',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                if ($value->giftcard_amount != 0) {
                    return trans('giftcard::app.giftcard.datagrid.active');
                } elseif ($value->giftcard_amount == 0){
                    return trans('giftcard::app.giftcard.datagrid.used');
                } else {
                    return trans('giftcard::app.giftcard.datagrid.expired');
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'creationdate',
            'label'      => trans('giftcard::app.giftcard.datagrid.creationdate'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                return \Carbon\Carbon::parse($value->creationdate)->format('Y-m-d'); // Adjust format as needed
            },
        ]);
        
        $this->addColumn([
            'index'      => 'expirationdate',
            'label'      => trans('giftcard::app.giftcard.datagrid.expirationdate'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                return \Carbon\Carbon::parse($value->expirationdate)->format('Y-m-d'); // Adjust format as needed
            },
        ]);
        
        $this->addColumn([
            'index'      => 'expirein',
            'label'      => trans('giftcard::app.giftcard.datagrid.expirein'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($value) {
                // Directly use the expirein value from the database
                if ($value->expirein == 0) {
                    return trans('Expiring today');
                } elseif ($value->expirein < 0) {
                    return trans('Expired');
                } else {
                    return $value->expirein . ' days';
                }
            },
        ]);                  

        $this->addColumn([
            'index'      => 'sendername',
            'label'      => trans('giftcard::app.giftcard.datagrid.sendername'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'senderemail',
            'label'      => trans('giftcard::app.giftcard.datagrid.senderemail'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'recipientname',
            'label'      => trans('giftcard::app.giftcard.datagrid.recipientname'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'recipientemail',
            'label'      => trans('giftcard::app.giftcard.datagrid.recipientemail'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }
}

    /**
     * Prepare actions.
     *
     * @return void
     */
//    public function prepareActions()
//    {
//        if (bouncer()->hasPermission('settings.giftcard.edit')) {
//            $this->addAction([
//                'index'  => 'edit',
//                'icon'   => 'icon-edit',
//                'title'  => trans('giftcard::app.giftcard.datagrid.edit'),
//                'method' => 'GET',
//                'url'    => function ($row) {
//                    return route('admin.giftcard.edit', $row->id);
//                },
//            ]);
//        }

//        if (bouncer()->hasPermission('settings.giftcard.delete')) {
//            $this->addAction([
//                'index'  => 'delete',
//                'icon'   => 'icon-delete',
//                'title'  => trans('giftcard::app.giftcard.datagrid.delete'),
//                'method' => 'DELETE',
//                'url'    => function ($row) {
//                    return route('admin.giftcard.delete', $row->id);
//                },
//            ]);
//        }
//    }
// }
