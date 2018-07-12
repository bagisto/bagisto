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
        DataGrid::make([
            'name' => 'admins',
            'table' => 'admins as a',
            'select' => 'a.id',
            'join' => [
                [
                    'join' => 'rightjoin',
                    'table' => 'roles as r',
                    'primaryKey' => 'a.role_id',
                    'condition' => '=',
                    'secondaryKey' => 'r.id',
                ]
            ],
            'columns' => [
                [
                    'name' => 'a.id',
                    'type' => 'string',
                    'label' => 'Admin ID',
                    'sortable' => true,
                    'filterable' => true,
                ],
                [
                    'name' => 'a.email',
                    'type' => 'string',
                    'label' => 'Admin E-Mail',
                    'sortable' => true,
                    'filterable' => true,
                ],
                // [
                //     'name' => 'r.name',
                //     'type' => 'string',
                //     'label' => 'Role Table ID',
                //     'sortable' => true,
                //     'filterable' => true,
                // ],
                [
                    'name' => 'r.name',
                    'type' => 'string',
                    'label' => 'Admin Name',
                    'sortable' => true,
                    'filterable' => false,
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
                        return '<a href="'.$value.'">' . $object->name . '</a>';
                    },
                ],

            ],
            // 'css' => []

        ]);

        $result = DataGrid::render();

        return $result;
    }
}
