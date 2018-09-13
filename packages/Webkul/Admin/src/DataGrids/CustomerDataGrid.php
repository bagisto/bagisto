<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;


/**
 * Customer DataGrid
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com> @rahul-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class CustomerDataGrid
{
    /**
     * The CustomerDataGrid
     * implementation.
     *
     * @var CustomerDataGrid
     */

    public function createCustomerDataGrid()
    {

            return DataGrid::make([
            'name' => 'Customer',
            'table' => 'customers',
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
                    'alias' => 'customerId',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'first_name',
                    'alias' => 'customerFirstName',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                ],
                [
                    'name' => 'email',
                    'alias' => 'customerEmail',
                    'type' => 'string',
                    'label' => 'Email',
                    'sortable' => true,
                ],
                [
                    'name' => 'phone',
                    'alias' => 'customerPhone',
                    'type' => 'number',
                    'label' => 'Phone',
                    'sortable' => true,
                ],
                [
                    'name' => 'customer_group_id',
                    'alias' => 'customerGroupId',
                    'type' => 'number',
                    'label' => 'Customer Group',
                    'sortable' => true,
                ],
            ],

            //don't use aliasing in case of filters

            'filterable' => [

                [
                    'name' => 'id',
                    'alias' => 'customerId',
                    'type' => 'number',
                    'label' => 'ID',
                ],
                [
                    'name' => 'first_name',
                    'alias' => 'customerFirstName',
                    'type' => 'string',
                    'label' => 'Name',
                ],
                [
                    'name' => 'phone',
                    'alias' => 'customerPhone',
                    'type' => 'number',
                    'label' => 'Phone',
                ],
                [
                    'name' => 'email',
                    'alias' => 'customerEmail',
                    'type' => 'string',
                    'label' => 'Comment',
                ],
                [
                    'name' => 'customer_group_id',
                    'alias' => 'customerGroupId',
                    'type' => 'number',
                    'label' => 'Status',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'first_name',
                    'type' => 'string',
                    'label' => 'Title',
                ],
                [
                    'column' => 'email',
                    'type' => 'string',
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

        return $this->createCustomerDataGrid()->render();

    }
}