<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Core\Models\CurrencyExchangeRate;
use Webkul\Ui\DataGrid\DataGrid;

class ExchangeRatesDataGrid extends DataGrid
{
    protected string $index = 'currency_exch_id';

    protected string $sortOrder = 'desc';

    public function prepareQueryBuilder(): void
    {
		$queryBuilder = CurrencyExchangeRate::from('currency_exchange_rates as cer')
											->leftJoin('currencies as curr', 'cer.target_currency', '=', 'curr.id')
											->addSelect('cer.id as currency_exch_id', 'curr.name', 'cer.rate');

		$this->addFilter('currency_exch_id', 'cer.id');

		$this->setQueryBuilder($queryBuilder);
	}

	/**
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 */
	public function addColumns(): void
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

	/**
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 */
	public function prepareActions(): void
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
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Exchange Rate']),
            'icon'         => 'icon trash-icon',
        ]);
    }
}
