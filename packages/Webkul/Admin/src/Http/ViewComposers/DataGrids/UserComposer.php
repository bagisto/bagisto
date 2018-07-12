<?php

namespace Webkul\Admin\Http\ViewComposers\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

// use App\Repositories\UserRepository;

class UserComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    // public function __construct(UserRepository $users)
    // {
    //     // Dependencies automatically resolved by service container...
    //     $this->users = $users;
    // }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $datagrid = DataGrid::make([
            'name' => 'admin',
            // 'select' => 'id',
            'table' => 'admins as a',
            'join' => [
                // [
                //     'join' => 'leftjoin',
                //     'table' => 'roles as r',
                //     'primaryKey' => 'a.role_id',
                //     'condition' => '=',
                //     'secondaryKey' => 'r.id',
                // ]
            ],
            'columns' => [
                [
                    'name' => 'a.id',
                    'type' => 'string',
                    'label' => 'Admin ID',
                    'sortable' => true,
                    'filterable' => false,
                ],
                [
                    'name' => 'a.name',
                    'type' => 'string',
                    'label' => 'Name',
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
                    // 'wrapper' => function ($value, $object) {
                    //     return '<a href="'.$value.'">' . $object->name . '</a>';
                    // },
                ],
                [
                    'name' => 'a.role_id',
                    'type' => 'string',
                    'label' => 'Admin Role ID',
                    'sortable' => true,
                    'filterable' => false,
                ],
                [
                    'name' => 'a.email',
                    'type' => 'string',
                    'label' => 'Admin Email',
                    'sortable' => true,
                    'filterable' => false,
                ],
                [
                    'name' => 'a.status',
                    'type' => 'string',
                    'label' => 'Admin Status',
                    'sortable' => true,
                    'filterable' => false,
                ],
            ],
            // 'css' => []

        ]);

        $view->with('datagrid', $datagrid);
        // $view->with('count', $this->users->count());
    }
}
