<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Product\Models\ProductReview;
use Webkul\Ui\DataGrid\DataGrid;

class CustomerReviewDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    protected string $index = 'product_review_id';

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
        $queryBuilder = ProductReview::from('product_reviews as pr')
            ->leftjoin('product_flat as pf', 'pr.product_id', '=', 'pf.product_id')
            ->select('pr.id as product_review_id', 'pr.title', 'pr.comment', 'pf.name as product_name', 'pr.status as product_review_status', 'pr.rating', 'pr.created_at')
            ->where('channel', core()->getCurrentChannelCode())
            ->where('locale', app()->getLocale());

        $this->addFilter('product_review_id', 'pr.id');
        $this->addFilter('product_review_status', 'pr.status');
        $this->addFilter('product_name', 'pf.name');
        $this->addFilter('created_at', 'pr.created_at');

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
            'index'      => 'rating',
            'label'      => trans('admin::app.customers.reviews.rating'),
            'type'       => 'number',
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
			'closure'    => function ($value) {
				switch ( $value->product_review_status ) {
					case 'approved':
						return '<span class="badge badge-md badge-success">' . trans('admin::app.datagrid.approved') . '</span>';
					case 'pending':
						return '<span class="badge badge-md badge-warning">' . trans('admin::app.datagrid.pending') . '</span>';
					case 'disapproved':
						return '<span class="badge badge-md badge-danger">' . trans('admin::app.datagrid.disapproved') . '</span>';
					default:
						return '';
				}
			},
		]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.datagrid.date'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);
    }

	/**
	 * Prepare actions.
	 *
	 * @throws \Webkul\Ui\Exceptions\ActionKeyException add action failed
	 * @return void
	 */
    public function prepareActions(): void
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

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'type'  => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.customer.review.massdelete'),
            'method' => 'POST',
        ]);

        $this->addMassAction([
            'type'    => 'update',
            'label'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('admin.customer.review.massupdate'),
            'method'  => 'POST',
            'options' => [
                trans('admin::app.customers.reviews.pending')     => 0,
                trans('admin::app.customers.reviews.approved')    => 1,
                trans('admin::app.customers.reviews.disapproved') => 2,
            ],
        ]);
    }
}
