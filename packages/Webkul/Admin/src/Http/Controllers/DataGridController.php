<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * DataGrid controller
 *
 * @author    Nikhil Malik <nikhil@webkul.com> @ysmnikhil
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class DataGridController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        DataGrid::make([
            'name' => 'Admin Datagrid',
            'table' => 'admins as u',
            'select' => 'u.id',
            'aliased' => true , //boolean to validate aliasing on the basis of this.
            'perpage' => 2,
            'filterable' => [
                [
                    'column' => 'u.id',
                    'type' => 'integer',
                    'label' => 'Admin ID'
                ], [
                    'column' => 'u.email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                ], [
                    'column' => 'u.name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                ]
            ],
            'searchables' =>[
                [
                    'name' => 'u.id',
                    'label' => 'ID',
                    'label' => 'Admin ID',
                ],
                [
                    'name' => 'u.name',
                    'label' => 'Name',
                    'label' => 'Admin Name',
                ]
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
            'columns' => [
                [
                    'name' => 'u.id',
                    'type' => 'string',
                    'label' => 'Admin ID',
                    'sortable' => true,
                    'filterable' => true
                ],
                [
                    'name' => 'u.email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                    'sortable' => true,
                    'filterable' => true
                ],
                [
                    'name' => 'u.name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                    'sortable' => true,
                    'filterable' => true,
                    // will create on run time query
                    // 'filter' => [
                    //     'function' => 'orwhere', // orwhere
                    //     'condition' => ['name', 'like', 'u'] // multiarray
                    // ],
                    'attributes' => [
                        'class' => 'class-a class-b',
                        'data-attr' => 'whatever you want',
                        'onclick' => "window.alert('alert from datagrid column')"
                     ],
                    'wrapper' => function ($value, $object) {
                        return '<a href="'.$value.'">' . $object->name . '</a>';
                    },
                ],
                [
                    'name' => 'r.name as x',
                    'type' => 'string',
                    'label' => 'Admin\'s Role',
                    'sortable' => true,
                    'filterable' => true,
                ],
                [
                    'name' => 'r.id as xx',
                    'type' => 'integer',
                    'label' => 'Role ID',
                    'sortable' => false,
                    'filterable' => false
                ],
            ],

            'operators' => [
                'eq' => "=",
                'lt' => "<",
                'gt' => ">",
                'lte' => "<=",
                'gte' => ">=",
                'neqn' => "!=",
                'ceq' => "<=>",
                'like' => "like",
                'nlike' => "not like",
            ],
            'mass_operations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',

                ]
            ],
            // 'css' => []

        ]);
        $result = DataGrid::render();
        return $result;
    }

    //for performing mass actions
    public function massAction()
    {
        $make = [
            'operations' =>[
                // [
                //     'route' => 'datagrid/update',
                //     'method' => 'post',
                //     'label' => 'Update',
                //     'type' => 'select',
                //     'name' => 'status',
                //     'values' => [
                //         [
                //             'label' => 'Enable',
                //             'value' => 1
                //         ], [
                //             'label' => 'Disable',
                //             'value' => 0
                //         ]
                //     ],
                // ],
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button'
                ]
            ]
        ];
        $result = DataGrid::makeMassAction($make);
    }

    public function massDelete(Request $r)
    {
        return $r;
    }
}
