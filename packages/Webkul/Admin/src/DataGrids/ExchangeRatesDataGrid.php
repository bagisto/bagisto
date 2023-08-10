<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ExchangeRatesDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'currency_exchange_id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('currency_exchange_rates')
            ->leftJoin('currencies', 'currency_exchange_rates.target_currency', '=', 'currencies.id')
            ->addSelect(
                'currency_exchange_rates.id as currency_exchange_id',
                'currencies.name as currency_name',
                'currency_exchange_rates.rate as currency_rate'
            );

        $this->addFilter('currency_exchange_id', 'currency_exchange_rates.id');
        $this->addFilter('currency_name', 'currencies.name');
        $this->addFilter('currency_rate', 'currency_exchange_rates.rate');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'currency_exchange_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'currency_name',
            'label'      => trans('admin::app.datagrid.currency-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'currency_rate',
            'label'      => trans('admin::app.datagrid.exch-rate'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon pencil-lg-icon',
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.exchange_rates.edit',
            'url'    => function ($row) {
                return route('admin.exchange_rates.edit', $row->currency_exchange_id);
            },
        ]);

        $this->addAction([
            'icon'         => 'icon trash-icon',
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'DELETE',
            'route'        => 'admin.exchange_rates.delete',
            'url'          => function ($row) {
                return route('admin.exchange_rates.delete', $row->currency_exchange_id);
            },
        ]);
    }
}
