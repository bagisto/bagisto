<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * orderDataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewsLetterDataGrid
{

    /**
     * The Data Grid implementation for orders
     */
    public function newsLetterDataGrid()
    {

            return DataGrid::make([
            'name' => 'Subscriberslist',
            'table' => 'subscribers_list as sublist',
            'select' => 'sublist.id',
            'perpage' => 10,
            'aliased' => false,
            //True in case of joins else aliasing key required on all cases

            'massoperations' =>[
                // [
                //     'route' => route('admin.datagrid.delete'),
                //     'method' => 'DELETE',
                //     'label' => 'Delete',
                //     'type' => 'button',
                // ],
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => 'admin.customers.subscribers.edit',
                    // 'confirm_text' => 'Do you really want to delete this record?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => 'admin.customers.subscribers.delete',
                    'confirm_text' => 'Do you really want to delete this record?',
                    'icon' => 'icon trash-icon',
                ],
            ],

            'join' => [],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'sublist.id',
                    'alias' => 'subid',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true
                ], [
                    'name' => 'sublist.is_subscribed',
                    'alias' => 'issubs',
                    'type' => 'string',
                    'label' => 'Subscribed',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 0)
                            return "False";
                        else
                            return "True";
                    },
                ], [
                    'name' => 'sublist.email',
                    'alias' => 'subsemail',
                    'type' => 'string',
                    'label' => 'Email',
                    'sortable' => true
                ]
            ],

            'filterable' => [
                [
                    'column' => 'sublist.id',
                    'alias' => 'subid',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'sublist.is_subscribed',
                    'alias' => 'issubs',
                    'type' => 'string',
                    'label' => 'Subscribed',
                ], [
                    'column' => 'sublist.email',
                    'alias' => 'subsemail',
                    'type' => 'string',
                    'label' => 'Email',
                ]
            ],
            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'sublist.id',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'sublist.is_subscribed',
                    'type' => 'string',
                    'label' => 'Subscribed',
                ], [
                    'column' => 'sublist.email',
                    'type' => 'string',
                    'label' => 'Email',
                ]
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
        return $this->newsLetterDataGrid()->render();
    }
}