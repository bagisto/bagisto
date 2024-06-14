<?php

namespace Webkul\Shop\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class DownloadableProductDataGrid extends DataGrid
{
    /**
     * Downloadable Product status Expired.
     */
    const STATUS_EXPIRED = 'expired';

    /**
     * Downloadable Product status Pending.
     */
    const STATUS_PENDING = 'pending';

    /**
     * Downloadable Product status Available
     */
    const STATUS_AVAILABLE = 'available';

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('downloadable_link_purchased')
            ->distinct()
            ->leftJoin('orders', 'downloadable_link_purchased.order_id', '=', 'orders.id')
            ->leftJoin('invoices', 'downloadable_link_purchased.order_id', '=', 'invoices.order_id')
            ->addSelect('downloadable_link_purchased.*', 'invoices.state as invoice_state', 'orders.increment_id')
            ->addSelect(DB::raw('('.DB::getTablePrefix().'downloadable_link_purchased.download_bought - '.DB::getTablePrefix().'downloadable_link_purchased.download_canceled - '.DB::getTablePrefix().'downloadable_link_purchased.download_used) as remaining_downloads'))
            ->where('downloadable_link_purchased.customer_id', auth()->guard('customer')->user()->id);

        $this->addFilter('status', 'downloadable_link_purchased.status');
        $this->addFilter('created_at', 'downloadable_link_purchased.created_at');
        $this->addFilter('increment_id', 'orders.increment_id');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'increment_id',
            'label'      => trans('shop::app.customers.account.downloadable-products.orderId'),
            'type'       => 'string',
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('shop::app.customers.account.downloadable-products.title'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                if (
                    $row->status == 'pending'
                    || $row->status == 'expired'
                    || $row->invoice_state !== 'paid'
                ) {
                    return $row->product_name;
                }

                return '<a class="text-blue-600" href="'.route('shop.customers.account.downloadable_products.download', $row->id).'" target="_blank">'.$row->product_name.'</a>';
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('shop::app.customers.account.downloadable-products.date'),
            'type'            => 'date',
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('shop::app.customers.account.downloadable-products.status'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('shop::app.customers.account.downloadable-products.expired'),
                    'value' => self::STATUS_EXPIRED,
                ],
                [
                    'label' => trans('shop::app.customers.account.downloadable-products.pending'),
                    'value' => self::STATUS_PENDING,
                ],
                [
                    'label' => trans('shop::app.customers.account.downloadable-products.available'),
                    'value' => self::STATUS_AVAILABLE,
                ],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_EXPIRED:
                        return '<p class="label-closed">'.trans('shop::app.customers.account.downloadable-products.expired').'</p>';

                    case self::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('shop::app.customers.account.downloadable-products.pending').'</p>';

                    case self::STATUS_AVAILABLE:
                        return '<p class="label-active">'.trans('shop::app.customers.account.downloadable-products.available').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'           => 'remaining_downloads',
            'label'           => trans('shop::app.customers.account.downloadable-products.remaining-downloads'),
            'type'            => 'datetime',
            'filterable_type' => 'datetime_range',
            'sortable'        => true,
            'closure'         => function ($row) {
                if (! $row->download_bought) {
                    return trans('shop::app.customer.account.downloadable_products.unlimited');
                }

                return $row->remaining_downloads;
            },
        ]);
    }
}
