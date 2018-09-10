<?php

namespace Webkul\Admin\Datagrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

class User
{

    /**
     * Create datagrid.
     *
     * @return void
     */
    public function createDatagrid()
    {

        return DataGrid::make([
            'name' => 'Admins',
            'table' => 'admins as u',
            'select' => 'u.id',
            'perpage' => 5,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' => [
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ]
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
                    'wrapper' => function ($value, $object) {
                                    return '<a class="color-red">' . $object->ID . '</a>';
                                },
                ], [
                    'name' => 'u.name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                    'wrapper' => function ($value, $object) {
                                    return '<a class="color-red">' . $object->Name . '</a>';
                                },
                ], [
                    'name' => 'u.email',
                    'alias' => 'Email',
                    'type' => 'string',
                    'label' => 'E-Mail',
                    'sortable' => true,
                ], [
                    'name' => 'r.name',
                    'alias' => 'xa',
                    'type' => 'string',
                    'label' => 'Role Name',
                    'sortable' => true,
                ], [
                    'name' => 'r.id',
                    'alias' => 'xc',
                    'type' => 'string',
                    'label' => 'Role ID',
                    'sortable' => true,
                ]
            ],
            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'u.name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Name'
                ], [
                    'column' => 'u.email',
                    'alias' => 'Email',
                    'type' => 'string',
                    'label' => 'Email'
                ], [
                    'column' => 'u.id',
                    'alias' => 'ID',
                    'type' => 'number',
                    'label' => 'Admin ID'
                ], [
                    'column' => 'r.id',
                    'alias' => 'Role_ID',
                    'type' => 'number',
                    'label' => 'Role ID'
                ]
            ],
            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'u.email',
                    'type' => 'string',
                    'label' => 'E-Mail'
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
        ]);
    }

    public function render()
    {
        return $this->createDatagrid()->render();
    }
}