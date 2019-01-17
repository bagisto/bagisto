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
    protected $index = 'product_review_id'; //the column that needs to be treated as index column

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_reviews as pr')->addSelect('pr.id as product_review_id', 'pr.title as product_review_title', 'pr.comment as product_review_comment', 'pg.name as product_name', 'pr.status as product_review_status')->leftjoin('products_grid as pg', 'pr.product_id', '=', 'pg.id');

        $this->addFilters('product_review_id', 'pr.id');
        $this->addFilters('product_review_title', 'pr.title');
        $this->addFilters('product_review_comment', 'pr.comment');
        $this->addFilters('product_name', 'pg.name');
        $this->addFilters('product_review_status', 'pr.status');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'product_review_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'product_review_title',
            'label' => trans('admin::app.datagrid.title'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'product_review_comment',
            'label' => trans('admin::app.datagrid.comment'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'product_name',
            'label' => trans('admin::app.datagrid.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'product_review_status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
            'closure' => true,
            'wrapper' => function ($value) {
                if ($value == 'approved')
                    return '<span class="badge badge-md badge-success">Approved</span>';
                else if ($value == "pending")
                    return '<span class="badge badge-md badge-warning">Pending</span>';
            },
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'route' => 'admin.customer.review.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
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
                'Disapprove' => 0,
                'Approve' => 1
            ]
        ]);
    }
}