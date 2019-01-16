<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * ExchangeRateDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ExchangeRatesDataGrid extends DataGrid
{
    protected $index = 'currency_exch_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('currency_exchange_rates as cer')->addSelect('cer.id as currency_exch_id', 'curr.name as currency_exch_name', 'cer.rate as currency_exch_rate')->leftJoin('currencies as curr', 'cer.target_currency', '=', 'curr.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'currency_exch_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'currency_exch_name',
            'label' => trans('admin::app.datagrid.currency-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'currency_exch_rate',
            'label' => trans('admin::app.datagrid.exch-rate'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'route' => 'admin.exchange_rates.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'route' => 'admin.exchange_rates.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Exchange Rate']),
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions() {
        // $this->prepareMassAction([
        //     'type' => 'delete',
        //     'action' => route('admin.catalog.products.massdelete'),
        //     'method' => 'DELETE'
        // ]);

        // $this->prepareMassAction([
        //     'type' => 'update',
        //     'action' => route('admin.catalog.products.massupdate'),
        //     'method' => 'PUT',
        //     'options' => [
        //         0 => true,
        //         1 => false,
        //     ]
        // ]);
    }
}