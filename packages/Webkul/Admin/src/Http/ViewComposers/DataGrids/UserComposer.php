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
            'aliased' => true, //boolean to validate aliasing on the basis of this.
            'filterable' => [
                [
                    'column' => 'u.email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail'
                ], [
                    'column' => 'u.name',
                    'type' => 'string',
                    'label' => 'Admin Name'
                ], [
                    'column' => 'u.id',
                    'type' => 'number',
                    'label' => 'Admin ID'
                ]
            ],
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
            'join' => [
                // [
                //     'join' => 'leftjoin',
                //     'table' => 'roles as r',
                //     'primaryKey' => 'u.role_id',
                //     'condition' => '=',
                //     'secondaryKey' => 'r.id',
                // ]
            ],
            'columns' => [
                [
                    'name' => 'u.id',
                    'type' => 'string',
                    'label' => 'Admin ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'u.name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                    'sortable' => true,
                ],
                [
                    'name' => 'u.email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                    'sortable' => true,
                ],
                // [
                //     'name' => 'r.name as rolename',
                //     'type' => 'string',
                //     'label' => 'Role Name',
                //     'sortable' => true,
                // ],
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
