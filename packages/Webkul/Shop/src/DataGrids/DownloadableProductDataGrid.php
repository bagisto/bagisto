<?php

namespace Webkul\Shop\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Sales\Models\DownloadableLinkPurchased;
use Webkul\Ui\DataGrid\DataGrid;

class DownloadableProductDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected string $index = 'id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected string $sortOrder = 'desc';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder(): void
    {
        $queryBuilder = DownloadableLinkPurchased::query()
            ->distinct()
            ->leftJoin('orders', 'downloadable_link_purchased.order_id', '=', 'orders.id')
            ->leftJoin('invoices', 'downloadable_link_purchased.order_id', '=', 'invoices.order_id')
            ->addSelect('downloadable_link_purchased.*', 'invoices.state as invoice_state', 'orders.increment_id')
            ->addSelect(DB::raw('(' . DB::getTablePrefix() . 'downloadable_link_purchased.download_bought - ' . DB::getTablePrefix() . 'downloadable_link_purchased.download_canceled - ' . DB::getTablePrefix() . 'downloadable_link_purchased.download_used) as remaining_downloads'))
            ->where('downloadable_link_purchased.customer_id', auth()->guard('customer')->user()->id);

        $this->addFilter('status', 'downloadable_link_purchased.status');
        $this->addFilter('created_at', 'downloadable_link_purchased.created_at');
        $this->addFilter('increment_id', 'orders.increment_id');

        $this->setQueryBuilder($queryBuilder);
    }

	/**
	 * Add columns.
	 *
	 * @throws \Webkul\Ui\Exceptions\ColumnKeyException add column failed
	 * @return void
	 */
	public function addColumns(): void
    {
        $this->addColumn([
            'index'      => 'increment_id',
            'label'      => trans('shop::app.customer.account.downloadable_products.order-id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

		$this->addColumn([
			'index'      => 'product_name',
			'label'      => trans('shop::app.customer.account.downloadable_products.name'),
			'type'       => 'string',
			'searchable' => true,
			'sortable'   => true,
			'filterable' => true,
			'closure'    => function ($value) {
				return ($value->status === 'pending' || $value->status === 'expired' || $value->invoice_state !== 'paid')
					? $value->product_name
					: $value->product_name . ' ' . '<a href="' . route('customer.downloadable_products.download', $value->id) . '" target="_blank">' . $value->name
					  . '</a>';
			},
		]);

		$this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('shop::app.customer.account.downloadable_products.date'),
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

		$this->addColumn([
			'index'      => 'status',
			'label'      => trans('shop::app.customer.account.downloadable_products.status'),
			'type'       => 'string',
			'searchable' => false,
			'sortable'   => true,
			'filterable' => true,
			'closure'    => function ($value) {
				switch ( $value->status ) {
					case 'pending':
						return trans('shop::app.customer.account.downloadable_products.pending');
					case 'available':
						return trans('shop::app.customer.account.downloadable_products.available');
					case 'expired':
						return trans('shop::app.customer.account.downloadable_products.expired');
					default:
						return '';
				}
			},
		]);

		$this->addColumn([
			'index'      => 'remaining_downloads',
			'label'      => trans('shop::app.customer.account.downloadable_products.remaining-downloads'),
			'type'       => 'datetime',
			'searchable' => false,
			'sortable'   => true,
			'filterable' => false,
			'closure'    => function ($value) {
				return !$value->download_bought
					? trans('shop::app.customer.account.downloadable_products.unlimited')
					: $value->remaining_downloads;
			},
		]);
    }
}
