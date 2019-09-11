<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * CustomerReviewDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerReviewDataGrid extends DataGrid
{
    protected $index = 'product_review_id'; //column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

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
        $this->setInvoker($this);

        $this->addColumn([
            'index' => 'product_review_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'title',
            'label' => trans('admin::app.datagrid.title'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => trans('admin::app.datagrid.comment'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('admin::app.datagrid.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => false
        ]);

        $this->addColumn([
            'index' => 'product_review_status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($value) {
                if ($value->product_review_status == 'approved')
                    return '<span class="badge badge-md badge-success">Approved</span>';
                else if ($value->product_review_status == "pending")
                    return '<span class="badge badge-md badge-warning">Pending</span>';
                else if ($value->product_review_status == "disapproved")
                    return '<span class="badge badge-md badge-danger">Disapproved</span>';
            },
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title' => 'Edit Customer Review',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'admin.customer.review.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'title' => 'Delete Customer Review',
            'method' => 'POST', // use GET request only for redirect purposes
            'route' => 'admin.customer.review.delete',
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions() {
        $this->addMassAction([
            'type' => 'delete',
            'label' => 'Delete',
            'action' => route('admin.customer.review.massdelete'),
            'method' => 'DELETE'
        ]);

        $this->addMassAction([
            'type' => 'update',
            'label' => 'Update Status',
            'action' => route('admin.customer.review.massupdate'),
            'method' => 'PUT',
            'options' => [
                'Pending' => 0,
                'Approve' => 1,
                'Disapprove' => 2
            ]
        ]);
    }
}