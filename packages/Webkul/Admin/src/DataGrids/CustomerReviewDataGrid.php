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

class CustomerReviewDataGrid
{
    /**
     * The CustomerReviewDataGrid
     * implementation.
     *
     * @var ReviewsDataGrid
     * for Reviews
     */

    public function createCustomerReviewDataGrid()
    {

            return DataGrid::make([
            'name' => 'Review',
            'table' => 'product_reviews',
            'select' => 'id',
            'perpage' => 10,
            'aliased' => false, //use this with false as default and true in case of joins

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
                // [
                //     'join' => 'leftjoin',
                //     'table' => 'roles as r',
                //     'primaryKey' => 'u.role_id',
                //     'condition' => '=',
                //     'secondaryKey' => 'r.id',
                // ]
            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [

                [
                    'name' => 'id',
                    'alias' => 'reviewId',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'title',
                    'alias' => 'titleName',
                    'type' => 'string',
                    'label' => 'Title',
                    'sortable' => true,
                ],
                [
                    'name' => 'rating',
                    'alias' => 'productRating',
                    'type' => 'number',
                    'label' => 'Rating',
                    'sortable' => true,
                ],
                [
                    'name' => 'comment',
                    'alias' => 'productComment',
                    'type' => 'string',
                    'label' => 'Comment',
                    'sortable' => true,
                ],
                [
                    'name' => 'status',
                    'alias' => 'countryStatus',
                    'type' => 'number',
                    'label' => 'Status',
                    'sortable' => true,
                ],

            ],

            //don't use aliasing in case of filters

            'filterable' => [
                [
                    'name' => 'id',
                    'alias' => 'countryId',
                    'type' => 'number',
                    'label' => 'ID',
                ],
                [
                    'name' => 'title',
                    'alias' => 'titleName',
                    'type' => 'string',
                    'label' => 'Title',
                ],
                [
                    'name' => 'rating',
                    'alias' => 'productRating',
                    'type' => 'number',
                    'label' => 'Rating',
                ],
                [
                    'name' => 'comment',
                    'alias' => 'productComment',
                    'type' => 'string',
                    'label' => 'Comment',
                ],
                [
                    'name' => 'status',
                    'alias' => 'countryStatus',
                    'type' => 'number',
                    'label' => 'Code',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'title',
                    'type' => 'string',
                    'label' => 'Title',
                ],
                [
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
        return $this->createCustomerReviewDataGrid()->render();
    }
}