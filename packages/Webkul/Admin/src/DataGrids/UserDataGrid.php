<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Users DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class UserDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var UserDataGrid
     * for admin users
     */

    public function createUserDataGrid()
    {

        return DataGrid::make([

            'name' => 'Admins',
            'table' => 'admins as u',
            'select' => 'u.id',
            'perpage' => 10,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' => [
                // [
                //     'route' => route('admin.datagrid.delete'),
                //     'method' => 'DELETE',
                //     'label' => 'Delete',
                //     'type' => 'button',
                // ],
                // [
                //     'route' => route('admin.datagrid.index'),
                //     'method' => 'POST',
                //     'label' => 'View Grid',
                //     'type' => 'select',
                //     'options' =>[
                //         1 => 'Edit',
                //         2 => 'Set',
                //         3 => 'Change Status'
                //     ]
                // ],
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ],
                [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to delete this record?',
                    'icon' => 'icon trash-icon',
                ],
            ],
            'join' => [
                [
                    'join' => 'leftjoin',
                    'table' => 'roles as r',
                    'primaryKey' => 'u.role_id',
                    'condition' => '=',
                    'secondaryKey' => 'r.id',
                ]
            ],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'u.id',
                    'alias' => 'ID',
                    'type' => 'string',
                    'label' => 'Admin ID',
                    'sortable' => true,
                    // 'wrapper' => function ($value, $object) {
                    //     return '<a class="color-red">' . $object->ID . '</a>';
                    // },
                ],
                [
                    'name' => 'u.name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                    // 'wrapper' => function ($value, $object) {
                    //     return '<a class="color-red">' . $object->Name . '</a>';
                    // },
                ],
                [
                    'name' => 'u.email',
                    'alias' => 'Email',
                    'type' => 'string',
                    'label' => 'Email',
                    'sortable' => true,
                ],
                [
                    'name' => 'r.name',
                    'alias' => 'xa',
                    'type' => 'string',
                    'label' => 'Role Name',
                    'sortable' => true,
                ],

            ],
            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'u.name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name'
                ],
                [
                    'column' => 'u.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'Admin ID'
                ],

            ],
            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'u.email',
                    'type' => 'string',
                    'label' => 'Email'
                ], [
                    'column' => 'u.name',
                    'type' => 'string',
                    'label' => 'Name'
                ]
            ],
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

    public function render() {

        return $this->createUserDataGrid()->render();

    }
}