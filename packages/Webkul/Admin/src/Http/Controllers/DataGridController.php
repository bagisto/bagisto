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
        // DataGrid::make([
        //     'name' => 'admin',
        //     'select' => 'a.id',
        //     'table' => 'admins as a',
        //     'join' => [
        //         [
        //             'join' => 'leftjoin',
        //             'table' => 'roles as r',
        //             'primaryKey' => 'a.role_id',
        //             'condition' => '=',
        //             'secondaryKey' => 'r.id',
        //         ]
        //     ],
        //     'columns' => [
        //         [
        //             'name' => 'a.id',
        //             'type' => 'string',
        //             'label' => 'Id',
        //             'sortable' => true,
        //             'filterable' => false,
        //         ],
        //         [
        //             'name' => 'a.name',
        //             'type' => 'string',
        //             'label' => 'Name',
        //             'sortable' => true,
        //             'filterable' => true,
        //             // will create on run time query
        //             'filter' => [
        //                 'function' => 'where', // andwhere
        //                 'condition' => ['a.name', '=', 'Admin'] // multiarray
        //             ],
        //             'attributes' => [
        //                 'class' => 'class-a class-b',
        //                 'data-attr' => 'whatever you want',
        //                 'onclick' => "window.alert('alert from datagrid column')"
        //              ],
        //             'wrapper' => function ($value, $object) {
        //                 return '<a href="'.$value.'">' . $object->name . '</a>';
        //             },
        //         ]
        //     ],
        //     // 'css' => []

        // ]);

        //Make case without any aliasing or joins
        /*
            operations list <,>,<=,>=,!=,=,like, IN.

            contains will get resolved by like after where and ranges can be resolved
            by using IN or where in (1,2,3)

            verbs => [
                'eq' => '=',
                'lt' => '<',
                'gt' => '>',
                'lte' => '<=',
                'gte' => '>=',
                'neq' => '!=',
                'inc_range' => '>x AND <y',
                'exc_range' => '>=x AND <=y',
                'not_inc_range' => '!>x AND <y',
                'not_exc_range' => '!>=x AND <=y',
            ]
        */

        $select_verbs = [
            0 => "aggregate",
            1 => "columns",
            2 => "from",
            3 => "joins",
            4 => "wheres",
            5 => "groups",
            6 => "havings",
            7 => "orders",
            8 => "limit",
            9 => "offset",
            10 => "lock"
        ];
        $bindings = [
            "select" => [],
            "from" => [],
            "join" => [],
            "where" => [],
            "having" => [],
            "order" => [],
            "union" => [],
        ];
        $operators = [
            'eq' => "=",
            'lt' => "<",
            'gt' => ">",
            'lte' => "<=",
            'gte' => ">=",
            'neqs' => "<>",
            'neqn' => "!=",
            'ceq' => "<=>",
            'like' => "like",
            'likebin' => "like binary",
            'ntlike' => "not like",
            'ilike' => "ilike",
            'regex' => "regexp",
            'notregex' => "not regexp",
            'simto' => "similar to",
            'nsimto' => "not similar to",
            'nilike' => "not ilike",
        ];
        DataGrid::make([
            'name' => 'authors',
            'table' => 'authors as a',
            'select' => 'a.id',
            'aliased' => true , //boolean to validate aliasing on the basis of this.
            'filterable' => [
                [
                    'column' => 'a.id',
                    'type' => 'integer'
                ], [
                    'column' => 'a.email',
                    'type' => 'string'
                ], [
                    'column' => 'a.first_name',
                    'type' => 'string'
                ]
            ],
            'join' => [
                [
                    'join' => 'leftjoin',
                    'table' => 'posts as p',
                    'primaryKey' => 'a.id',
                    'condition' => '=',
                    'secondaryKey' => 'p.author_id',
                ]
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
                [
                    'name' => 'p.content',
                    'type' => 'string',
                    'label' => 'Content',
                    'sortable' => true,
                    'filterable' => false,
                ],
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
            'select_verbs' => [
                0 => "aggregate",
                1 => "columns",
                2 => "from",
                3 => "joins",
                4 => "wheres",
                5 => "groups",
                6 => "havings",
                7 => "orders",
                8 => "limit",
                9 => "offset",
                10 => "lock"
            ],
            'operators' => [
                'eq' => "=",
                'lt' => "<",
                'gt' => ">",
                'lte' => "<=",
                'gte' => ">=",
                'neqs' => "<>",
                'neqn' => "!=",
                'ceq' => "<=>",
                'like' => "like",
                'likebin' => "like binary",
                'ntlike' => "not like",
                'ilike' => "ilike",
                'regex' => "regexp",
                'notregex' => "not regexp",
                'simto' => "similar to",
                'nsimto' => "not similar to",
                'nilike' => "not ilike",
            ],
            // 'css' => []

        ]);

        $result = DataGrid::render();

        return $result;
    }
}
