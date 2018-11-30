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
            'table' => 'customers as cus',
            'select' => 'cus.id',
            'perpage' => 10,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                // [
                //     'route' => route('admin.datagrid.delete'),
                //     'method' => 'DELETE',
                //     'label' => 'Delete',
                //     'type' => 'button', //select || button only
                // ],
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to delete this record?',
                    'icon' => 'icon trash-icon',
                ],
            ],

            'join' => [
                [
                    'join' => 'leftjoin',
                    'table' => 'customer_groups as cg',
                    'primaryKey' => 'cus.customer_group_id',
                    'condition' => '=',
                    'secondaryKey' => 'cg.id',
                ]
            ],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'cus.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'CONCAT(first_name, " ", last_name)',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                ], [
                    'name' => 'email',
                    'alias' => 'Email',
                    'type' => 'string',
                    'label' => 'Email',
                    'sortable' => false,
                ], [
                    'name' => 'cg.name',
                    'alias' => 'CustomerGroupName',
                    'type' => 'string',
                    'label' => 'Group Name',
                    'sortable' => false,
                ],
            ],

            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'cus.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'email',
                    'alias' => 'Email',
                    'type' => 'string',
                    'label' => 'Email',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'FirstName',
                    'type' => 'string',
                    'label' => 'First Name',
                ], [
                    'column' => 'email',
                    'alias' => 'Email',
                    'type' => 'string',
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