<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class ExchangeRatesDataGrid extends DataGrid
{
    protected $index = 'currency_exch_id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('currency_exchange_rates as cer')
            ->leftJoin('currencies as curr', 'cer.target_currency', '=', 'curr.id')
            ->addSelect('cer.id as currency_exch_id', 'curr.name', 'cer.rate');

        $this->addFilter('currency_exch_id', 'cer.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'currency_exch_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.currency-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'rate',
            'label'      => trans('admin::app.datagrid.exch-rate'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.exchange_rates.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.exchange_rates.delete',
            'confirm_text' => trans('ui::app.datagrid.mass-action.delete', ['resource' => 'Exchange Rate']),
            'icon'         => 'icon trash-icon',
        ]);
    }
}