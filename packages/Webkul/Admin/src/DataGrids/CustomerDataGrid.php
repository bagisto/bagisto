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
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button', //select || button only
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

            ],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'first_name',
                    'alias' => 'FirstName',
                    'type' => 'string',
                    'label' => 'First Name',
                    'sortable' => false,
                ],
                [
                    'name' => 'email',
                    'alias' => 'Email',
                    'type' => 'string',
                    'label' => 'Email',
                    'sortable' => false,
                ],
                [
                    'name' => 'phone',
                    'alias' => 'Phone',
                    'type' => 'number',
                    'label' => 'Phone',
                    'sortable' => true,
                ],
                [
                    'name' => 'customer_group_id',
                    'alias' => 'CustomerGroupId',
                    'type' => 'number',
                    'label' => 'Group ID',
                    'sortable' => false,
                ],
            ],

            //don't use aliasing in case of filters

            'filterable' => [

                [
                    'column' => 'id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'ID',
                ],
                [
                    'column' => 'first_name',
                    'alias' => 'FirstName',
                    'type' => 'string',
                    'label' => 'First Name',
                ]
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'FirstName',
                    'type' => 'string',
                    'label' => 'First Name',
                ],
                [
                    'column' => 'email',
                    'type' => 'string',
                    'label' => 'Email',
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