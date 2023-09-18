<?php

namespace Webkul\Admin\DataGrids\Settings;

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
            'label'      => trans('admin::app.settings.exchange-rates.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'currency_name',
            'label'      => trans('admin::app.settings.exchange-rates.index.datagrid.currency-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'currency_rate',
            'label'      => trans('admin::app.settings.exchange-rates.index.datagrid.exchange-rate'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    public function prepareActions()
    {
        if (bouncer()->hasPermission('settings.exchange_rates.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.settings.exchange-rates.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.settings.exchange_rates.edit', $row->currency_exchange_id);
                },
            ]);
        }

        if (bouncer()->hasPermission('settings.exchange_rates.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.settings.exchange-rates.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.settings.exchange_rates.delete', $row->currency_exchange_id);
                },
            ]);
        }
    }
}
