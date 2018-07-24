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
        // $select_verbs = [
        //     0 => "aggregate",
        //     1 => "columns",
        //     2 => "from",
        //     3 => "joins",
        //     4 => "wheres",
        //     5 => "groups",
        //     6 => "havings",
        //     7 => "orders",
        //     8 => "limit",
        //     9 => "offset",
        //     10 => "lock"
        // ];
        // $bindings = [
        //     "select" => [],
        //     "from" => [],
        //     "join" => [],
        //     "where" => [],
        //     "having" => [],
        //     "order" => [],
        //     "union" => [],
        // ];
        // $operators = [
        //     'eq' => "=",
        //     'lt' => "<",
        //     'gt' => ">",
        //     'lte' => "<=",
        //     'gte' => ">=",
        //     'neqs' => "<>",
        //     'neqn' => "!=",
        //     'ceq' => "<=>",
        //     'like' => "like",
        //     'likebin' => "like binary",
        //     'ntlike' => "not like",
        //     'ilike' => "ilike",
        //     'regex' => "regexp",
        //     'notregex' => "not regexp",
        //     'simto' => "similar to",
        //     'nsimto' => "not similar to",
        //     'nilike' => "not ilike",
        // ];
        DataGrid::make([
            'name' => 'authors',
            'table' => 'authors as a',
            'select' => 'a.id',
            'aliased' => true , //boolean to validate aliasing on the basis of this.
            'filterable' => [
                [
                    'column' => 'a.id',
                    'type' => 'integer',
                    'label' => 'Admin ID'
                ], [
                    'column' => 'a.email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                ], [
                    'column' => 'a.first_name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                ]
            ],
            'searchables' =>[
                [
                    'name' => 'a.id',
                    'label' => 'ID',
                    'label' => 'Admin ID',
                ],
                [
                    'name' => 'a.name',
                    'label' => 'Name',
                    'label' => 'Admin Name',
                ]
            ],
            'join' => [
                // [
                //     'join' => 'leftjoin',
                //     'table' => 'posts as p',
                //     'primaryKey' => 'a.id',
                //     'condition' => '=',
                //     'secondaryKey' => 'p.author_id',
                // ]
            ],
            'columns' => [
                [
                    'name' => 'a.id',
                    'type' => 'string',
                    'label' => 'Admin ID',
                    'sortable' => true,
                    'filterable' => true
                ],
                [
                    'name' => 'a.email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                    'sortable' => true,
                    'filterable' => true
                ],
                // [
                //     'name' => 'p.content as pp',
                //     'type' => 'string',
                //     'label' => 'Content',
                //     'sortable' => true,
                //     'filterable' => false,
                // ],
                [
                    'name' => 'a.first_name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                    'sortable' => true,
                    'filterable' => true,
                    // will create on run time query
                    // 'filter' => [
                    //     'function' => 'where', // orwhere
                    //     'condition' => ['name', '=', 'Admin'] // multiarray
                    // ],
                    'attributes' => [
                        'class' => 'class-a class-b',
                        'data-attr' => 'whatever you want',
                        'onclick' => "window.alert('alert from datagrid column')"
                     ],
                    'wrapper' => function ($value, $object) {
                        return '<a href="'.$value.'">' . $object->first_name . '</a>';
                    },
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
        // $prepareMassAction = DataGrid::massAction();
        // if ($prepareMassAction) {
        //     $result = DataGrid::render();
        //     return $result;
        // } else {
        //     throw new \Exception('Mass Actions Attributes Have Some Unknown Problems');
        // }
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
        // return $result;
    }

    public function massDelete(Request $r)
    {
        return $r;
    }
}
