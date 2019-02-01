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
    protected $paginate = true;

    protected $index = 'product_review_id'; //column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_reviews as pr')
                ->leftjoin('products_grid as pg', 'pr.product_id', '=', 'pg.id')
                ->addSelect('pr.id as product_review_id', 'pr.title', 'pr.comment', 'pg.name', 'pr.status as product_review_status');

        $this->addFilter('product_review_id', 'pr.id');
        $this->addFilter('product_review_status', 'pr.status');

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
        ]);

        $this->addColumn([
            'index' => 'title',
            'label' => trans('admin::app.datagrid.title'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => trans('admin::app.datagrid.comment'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('admin::app.datagrid.product-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
        ]);

        $this->addColumn([
            'index' => 'product_review_status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
            'closure' => true,
            'wrapper' => function ($value) {
                if ($value->product_review_status == 'approved')
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