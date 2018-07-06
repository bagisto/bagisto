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
            'name' => 'admin',
            'select' => 'a.id',
            'table' => 'admins as a',
            'join' => [
                [
                    'join' => 'leftjoin',
                    'table' => 'roles as r',
                    'primaryKey' => 'a.role_id',
                    'condition' => '=',
                    'secondryKey' => 'r.id',
                ]
            ],
            'columns' => [
                [
                    'name' => 'a.id as aila',
                    'type' => 'string',
                    'label' => 'Id',
                    'sortable' => true,
                ],
                [
                    'name' => 'a.name',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                    'filterable' => false,
                    // will create on run time query
                    // 'filter' => [
                    //     'function' => 'where', // andwhere
                    //     'condition' => ['u.user_id', '=', '1'] // multiarray
                    // ],
                    'attributes' => [
                        'class' => 'class-a class-b',
                        'data-attr' => 'whatever you want',
                        'onclick' => "window.alert('alert from datagrid column')"
                     ],
                    'wrapper' => function($value, $object){
                        return '<a href="'.$value.'">' . $object->name . '</a>';
                    },
                ]
            ],
            // 'css' => []

        ]);

        $result = DataGrid::render();

        // dump($result);
        // dd('datagrid');

        return $result;
    }
}