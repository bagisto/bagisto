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

        $verbs = [
            'eq' => '=',
            'lt' => '<',
            'gt' => '>',
            'lte' => '<=',
            'gte' => '>=',
            'neq' => 'not =',
            'inc_range' => '>x AND <y', //cummutative
            'exc_range' => '>=x AND <=y',
            'not_inc_range' => 'not >x AND <y',
            'not_exc_range' => 'not >=x AND <=y',
        ];

        DataGrid::make([
            'name' => 'posts',
            'table' => 'authors',
            'select' => 'id',
            'filterable' => [
                [
                    'column' => 'id',
                    'type' => 'integer'
                ], [
                    'column' => 'email',
                    'type' => 'string'
                ], [
                    'column' => 'first_name',
                    'type' => 'string'
                ]
            ],
            'join' => [
                // [
                //     'join' => 'rightjoin',
                //     'table' => 'roles as r',
                //     'primaryKey' => 'a.role_id',
                //     'condition' => '=',
                //     'secondaryKey' => 'r.id',
                // ]
            ],
            'columns' => [
                [
                    'name' => 'id',
                    'type' => 'string',
                    'label' => 'Admin ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                    'sortable' => true,
                ],
                // [
                //     'name' => 'r.name',
                //     'type' => 'string',
                //     'label' => 'Role Table ID',
                //     'sortable' => true,
                //     'filterable' => true,
                // ],
                [
                    'name' => 'first_name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                    'sortable' => true,
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
            'verbs' => [
                'eq' => '=',
                'lt' => '<',
                'gt' => '>',
                'lte' => '<=',
                'gte' => '>=',
                'neq' => 'not =',
                'contains' => 'like',
                'inc_range' => '>x AND <y',
                'exc_range' => '>=x AND <=y',
                'not_inc_range' => 'not >x AND <y',
                'not_exc_range' => 'not >=x AND <=y',
            ]
            // 'css' => []

        ]);

        $result = DataGrid::render();

        return $result;
    }
}
