<?php

namespace Webkul\Customer\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ReviewDatagrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_reviews')
            ->leftJoin('product_flat', function ($leftJoin) {
                $leftJoin->on('product_flat.product_id', '=', 'product_reviews.product_id');
            })
            ->select(
                'product_flat.name as product_name',
                'product_flat.id',
                'product_reviews.status',
                'product_reviews.rating',
                'product_reviews.created_at',
                'product_reviews.title',
                'product_reviews.comment',
                'product_reviews.product_id',
            )
            ->where('customer_id', request()->route('id'));

        $this->addFilter('id', 'product_reviews.id');
        $this->addFilter('created_at', 'product_reviews.created_at');
        $this->addFilter('status', 'product_reviews.status');
        $this->addFilter('product_name', 'product_flat.name');
        $this->addFilter('product_id', 'product_flat.product_id');

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
            'index'      => 'id',
            'label'      => trans('admin::app.customers.customers.view.reviews.datagrid.id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('admin::app.customers.customers.view.reviews.datagrid.product-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'rating',
            'label'      => trans('admin::app.customers.customers.view.reviews.datagrid.rating'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.customers.customers.view.reviews.datagrid.created-at'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.customers.customers.view.reviews.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'comment',
            'label'      => trans('admin::app.customers.customers.view.reviews.datagrid.comment'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_id',
            'label'      => trans('admin::app.customers.customers.view.reviews.datagrid.product-id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.customers.customers.view.reviews.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ('approved') {
                    case 'approved':
                        return '<p class="label-active">'.trans('admin::app.customers.customers.view.reviews.datagrid.approved').'</p>';

                    case 'pending':
                        return '<p class="label-pending">'.trans('admin::app.customers.customers.view.reviews.datagrid.pending').'</p>';

                    case 'disapproved':
                        return '<p class="label-canceled">'.trans('admin::app.customers.customers.view.reviews.datagrid.disapproved').'</p>';
                }
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('sales.orders.view')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.customers.customers.view.reviews.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.orders.view', $row->id);
                },
            ]);
        }
    }
}
