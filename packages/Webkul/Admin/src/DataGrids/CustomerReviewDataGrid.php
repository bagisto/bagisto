<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class CustomerReviewDataGrid extends DataGrid
{
    protected $index = 'product_review_id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_reviews as pr')
            ->leftjoin('product_flat as pf', 'pr.product_id', '=', 'pf.product_id')
            ->select('pr.id as product_review_id', 'pr.title', 'pr.comment', 'pf.name as product_name', 'pr.status as product_review_status')
            ->where('channel', core()->getCurrentChannelCode())
            ->where('locale', app()->getLocale());

        $this->addFilter('product_review_id', 'pr.id');
        $this->addFilter('product_review_status', 'pr.status');
        $this->addFilter('product_name', 'pf.name');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'product_review_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'comment',
            'label'      => trans('admin::app.datagrid.comment'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('admin::app.datagrid.product-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'product_review_status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'width'      => '100px',
            'filterable' => true,
            'closure'    => true,
            'wrapper'    => function ($value) {
                if ($value->product_review_status == 'approved') {
                    return '<span class="badge badge-md badge-success">' . trans('admin::app.datagrid.approved') . '</span>';
                } elseif ($value->product_review_status == "pending") {
                    return '<span class="badge badge-md badge-warning">' . trans('admin::app.datagrid.pending') . '</span>';
                } elseif ($value->product_review_status == "disapproved") {
                    return '<span class="badge badge-md badge-danger">' . trans('admin::app.datagrid.disapproved') . '</span>';
                }
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.customer.review.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.customer.review.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type'  => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.customer.review.massdelete'),
            'method' => 'DELETE',
        ]);

        $this->addMassAction([
            'type'    => 'update',
            'label'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('admin.customer.review.massupdate'),
            'method'  => 'PUT',
            'options' => [
                trans('admin::app.customers.reviews.pending')     => 0,
                trans('admin::app.customers.reviews.approved')    => 1,
                trans('admin::app.customers.reviews.disapproved') => 2,
            ],
        ]);
    }
}