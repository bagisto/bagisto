<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CustomerReviewDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return string
     */
    protected $primaryColumn = 'product_review_id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_reviews as pr')
            ->leftJoin('product_flat as pf', 'pr.product_id', '=', 'pf.product_id')
            ->leftJoin('customers as c', 'pr.customer_id', '=', 'c.id')
            ->select(
                'pr.id as product_review_id',
                'pr.title',
                'pr.comment',
                'pf.name as product_name',
                'pr.status as product_review_status',
                'pr.rating',
                'pr.created_at',
                DB::raw('CONCAT(' . DB::getTablePrefix() . 'c.first_name, " ", ' . DB::getTablePrefix() . 'c.last_name) as customer_full_name')
            )
            ->where('channel', core()->getCurrentChannelCode())
            ->where('locale', app()->getLocale());

        $this->addFilter('product_review_id', 'pr.id');
        $this->addFilter('product_review_status', 'pr.status');
        $this->addFilter('product_name', 'pf.name');
        $this->addFilter('created_at', 'pr.created_at');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        // Customer Name
        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.customer_names'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.product'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_review_status',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);


        $this->addColumn([
            'index'      => 'rating',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.rating'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.date'),
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_review_id',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'comment',
            'label'      => trans('admin::app.customers.reviews.index.datagrid.comment'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.customers.reviews.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.customer.review.edit', $row->product_review_id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.customers.reviews.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.customer.review.delete', $row->product_review_id);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'title'  => trans('admin::app.customers.reviews.index.datagrid.delete'),
            'action' => route('admin.customer.review.mass_delete'),
            'method' => 'POST',
        ]);

        $this->addMassAction([
            'title'   => trans('admin::app.customers.reviews.index.datagrid.update-status'),
            'method'  => 'POST',
            'action'  => route('admin.customer.review.mass_update'),
            'options' => [
                trans('admin::app.customers.reviews.index.datagrid.pending')     => 0,
                trans('admin::app.customers.reviews.index.datagrid.approved')    => 1,
                trans('admin::app.customers.reviews.index.datagrid.disapproved') => 2,
            ],
        ]);
    }
}
