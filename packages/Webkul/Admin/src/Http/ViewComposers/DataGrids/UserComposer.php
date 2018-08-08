<?php

namespace Webkul\Admin\Http\ViewComposers\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

// use App\Repositories\UserRepository;

class UserComposer
{
    /**
     * The Data Grid implementation.
     *
     * @var UserComposer
     * for admin
     */
    protected $users;


    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $datagrid = DataGrid::make([
            'name' => 'Admins',
            'table' => 'admins as u',
            'select' => 'u.id',
            'perpage' => 5,
            'aliased' => true, //use this with false as default and true in case of joins

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
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
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon trash-icon',
                ], [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon pencil-lg-icon',
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
                ],
                [
                    'name' => 'u.name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                    'sortable' => true,
                    'wrapper' => function ($value, $object) {
                                    return '<a class="color-red">' . $object->Name . '</a>';
                                },
                ],
                [
                    'name' => 'u.email',
                    'alias' => 'Email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                    'sortable' => true,
                ],
                [
                    'name' => 'r.name',
                    'alias' => 'xa',
                    'type' => 'string',
                    'label' => 'Role Name',
                    'sortable' => true,
                ],
                [
                    'name' => 'r.id',
                    'alias' => 'xc',
                    'type' => 'string',
                    'label' => 'Role ID',
                    'sortable' => true,
                ],
                // [
                //     'name' => 'a.first_name',
                //     'type' => 'string',
                //     'label' => 'Admin Name',
                //     'sortable' => true,
                //     'filterable' => true,
                //     // will create on run time query
                //     // 'filter' => [
                //     //     'function' => 'where', // orwhere
                //     //     'condition' => ['name', '=', 'Admin'] // multiarray
                //     // ],
                //     'attributes' => [
                //         'class' => 'class-a class-b',
                //         'data-attr' => 'whatever you want',
                //         'onclick' => "window.alert('alert from datagrid column')"
                //      ],
                //     'wrapper' => function ($value, $object) {
                //         return '<a href="'.$value.'">' . $object->first_name . '</a>';
                //     },
                // ],

            ],
            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'u.name',
                    'alias' => 'Name',
                    'type' => 'string',
                    'label' => 'Admin Name'
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
                    'label' => 'Admin E-Mail'
                ], [
                    'column' => 'u.name',
                    'type' => 'string',
                    'label' => 'Admin Name'
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

        $view->with('datagrid', $datagrid);
        // $view->with('count', $this->users->count());
    }
}