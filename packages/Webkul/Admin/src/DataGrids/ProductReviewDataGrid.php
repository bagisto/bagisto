<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;


/**
 * Review DataGrid
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com> @rahul-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ProductReviewDataGrid
{
    /**
     * The ProductReviewDataGrid
     * implementation.
     *
     * @var CustomerReviewsDataGrid
     * for Reviews
     */

    public function createProductReviewDataGrid()
    {

            return DataGrid::make([
            'name' => 'Review',
            'table' => 'product_reviews as pr',
            'select' => 'pr.id',
            'perpage' => 10,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon trash-icon',
                ],
            ],

            'join' => [
                [
                    'join' => 'leftjoin',
                    'table' => 'products_grid as pt',
                    'primaryKey' => 'pr.product_id',
                    'condition' => '=',
                    'secondaryKey' => 'pt.product_id',
                ]
            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [

                [
                    'name' => 'pr.id',
                    'alias' => 'reviewId',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'pr.title',
                    'alias' => 'titleName',
                    'type' => 'string',
                    'label' => 'Title',
                    'sortable' => true,
                ], [
                    'name' => 'pr.comment',
                    'alias' => 'productComment',
                    'type' => 'string',
                    'label' => 'Comment',
                    'sortable' => true,
                ],
                [
                    'name' => 'pt.name',
                    'alias' => 'productName',
                    'type' => 'string',
                    'label' => 'Product Name',
                    'sortable' => true,
                ],
                [
                    'name' => 'pr.status',
                    'alias' => 'reviewStatus',
                    'type' => 'number',
                    'label' => 'Status',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 'approved')
                            return '<span class="badge badge-md badge-success">Approved</span>';
                        else if($value == "pending")
                            return '<span class="badge badge-md badge-warning">Pending</span>';
                    },
                ],
            ],

            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'pr.id',
                    'alias' => 'reviewId',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'pr.title',
                    'alias' => 'titleName',
                    'type' => 'string',
                    'label' => 'Title',
                ], [
                    'column' => 'pr.comment',
                    'alias' => 'productComment',
                    'type' => 'string',
                    'label' => 'Comment',
                ], [
                    'column' => 'pt.name',
                    'alias' => 'productName',
                    'type' => 'string',
                    'label' => 'Product Name',
                ],[
                    'column' => 'pr.status',
                    'alias' => 'reviewStatus',
                    'type' => 'string',
                    'label' => 'Status',
                ],
            ],

            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'title',
                    'type' => 'string',
                    'label' => 'Title',
                ], [
                    'column' => 'rating',
                    'type' => 'number',
                    'label' => 'Rating',
                ],
            ],

            //list of viable operators that will be used
            'operators' => [
                'eq' => "=",
                'lt' => "<",
                'gt' => ">",
                'lte' => "<=",
                'gte' => ">=",
                'neqs' => "<>",
                'neqn' => "!=",
                'like' => "like",
                'nlike' => "not like",
            ],
            // 'css' => []

        ]);

    }

    public function render()
    {
        return $this->createProductReviewDataGrid()->render();
    }
}