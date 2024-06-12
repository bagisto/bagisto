<?php

namespace Webkul\Admin\DataGrids\Customers\View;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ReviewDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @var string
     */
    protected $primaryColumn = 'product_review_id';

    /**
     * Review status "approved".
     */
    const STATUS_APPROVED = 'approved';

    /**
     * Review status "pending", indicating awaiting approval or processing.
     */
    const STATUS_PENDING = 'pending';

    /**
     * Review status "disapproved", indicating rejection or denial.
     */
    const STATUS_DISAPPROVED = 'disapproved';

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
                'product_reviews.id as product_review_id',
                'product_reviews.status',
                'product_reviews.rating',
                'product_reviews.created_at',
                'product_reviews.title',
                'product_reviews.comment',
                'product_reviews.product_id',
            )
            ->where('customer_id', request()->route('id'))
            ->where('channel', core()->getCurrentChannelCode())
            ->where('locale', app()->getLocale());

        $this->addFilter('product_review_id', 'product_reviews.id');
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
            'index'      => 'product_review_id',
            'label'      => trans('admin::app.customers.customers.view.datagrid.reviews.id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('admin::app.customers.customers.view.datagrid.reviews.product-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.customers.customers.view.datagrid.reviews.title'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index' => 'comment',
            'label' => trans('admin::app.customers.customers.view.datagrid.reviews.comment'),
            'type'  => 'string',
        ]);

        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('admin::app.customers.customers.view.datagrid.reviews.product-id'),
            'type'  => 'string',
        ]);

        $this->addColumn([
            'index'              => 'status',
            'label'              => trans('admin::app.customers.customers.view.datagrid.reviews.status'),
            'type'               => 'string',
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => [
                [
                    'label' => trans('admin::app.customers.reviews.index.datagrid.approved'),
                    'value' => self::STATUS_APPROVED,
                ],
                [
                    'label' => trans('admin::app.customers.reviews.index.datagrid.pending'),
                    'value' => self::STATUS_PENDING,
                ],
                [
                    'label' => trans('admin::app.customers.reviews.index.datagrid.disapproved'),
                    'value' => self::STATUS_DISAPPROVED,
                ],
            ],
            'sortable'   => true,
            'closure'    => function ($row) {
                switch ($row->status) {
                    case self::STATUS_APPROVED:
                        return '<p class="label-active">'.trans('admin::app.customers.customers.view.datagrid.reviews.approved').'</p>';

                    case self::STATUS_PENDING:
                        return '<p class="label-pending">'.trans('admin::app.customers.customers.view.datagrid.reviews.pending').'</p>';

                    case self::STATUS_DISAPPROVED:
                        return '<p class="label-canceled">'.trans('admin::app.customers.customers.view.datagrid.reviews.disapproved').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'              => 'rating',
            'label'              => trans('admin::app.customers.customers.view.datagrid.reviews.rating'),
            'type'               => 'string',
            'searchable'         => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => array_map(function ($value) {
                return [
                    'label'  => $value,
                    'value'  => (string) $value,
                ];
            },
                range(1, 5)),
            'sortable'           => true,
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.customers.customers.view.datagrid.reviews.created-at'),
            'type'            => 'date',
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'sortable'        => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('catalog.products.edit')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.customers.customers.view.datagrid.reviews.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.orders.view', $row->id);
                },
            ]);
        }
    }
}
