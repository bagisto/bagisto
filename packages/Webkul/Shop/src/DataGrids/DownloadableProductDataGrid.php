<?php

namespace Webkul\Shop\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * DownloadableProduct DataGrid class
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class DownloadableProductDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('downloadable_link_purchased')
                ->leftJoin('orders', 'downloadable_link_purchased.order_id', '=', 'orders.id')
                ->addSelect('downloadable_link_purchased.*', 'orders.increment_id')
                ->addSelect(DB::raw('(downloadable_link_purchased.download_bought - downloadable_link_purchased.download_used) as remaining_downloads'))
                ->where('downloadable_link_purchased.customer_id', auth()->guard('customer')->user()->id);

        $this->addFilter('status', 'downloadable_link_purchased.status');
        $this->addFilter('created_at', 'downloadable_link_purchased.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'increment_id',
            'label' => trans('shop::app.customer.account.downloadable_products.order-id'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('shop::app.customer.account.downloadable_products.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($value) {
                if ($value->status == 'pending' || $value->status == 'expired') {
                    return $value->product_name;
                } else {
                    return $value->product_name . ' ' . '<a href="' . route('customer.downloadable_products.download', $value->id) . '" target="_blank">' . $value->name . '</a>';
                }
            },
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('shop::app.customer.account.downloadable_products.date'),
            'type' => 'datetime',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('shop::app.customer.account.downloadable_products.status'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($value) {
                if ($value->status == 'pending')
                    return trans('shop::app.customer.account.downloadable_products.pending');
                else if ($value->status == 'available')
                    return trans('shop::app.customer.account.downloadable_products.available');
                else if ($value->status == 'expired')
                    return trans('shop::app.customer.account.downloadable_products.expired');
            },
        ]);

        $this->addColumn([
            'index' => 'remaining_downloads',
            'label' => trans('shop::app.customer.account.downloadable_products.remaining-downloads'),
            'type' => 'datetime',
            'searchable' => false,
            'sortable' => false,
            'filterable' => false,
            'closure' => true,
            'wrapper' => function ($value) {
                if (! $value->download_bought)
                    return trans('shop::app.customer.account.downloadable_products.unlimited');
                
                return $value->remaining_downloads;
            },
        ]);
    }
}