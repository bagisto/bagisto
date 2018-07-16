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
            'name' => 'Users',
            'table' => 'admins as u',
            'select' => 'u.id',
            'aliased' => true , //boolean to validate aliasing on the basis of this.
            'filterable' => [
                [
                    'column' => 'u.id',
                    'type' => 'integer'
                ], [
                    'column' => 'u.email',
                    'type' => 'string'
                ], [
                    'column' => 'u.name',
                    'type' => 'string'
                ]
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
                //     'name' => 'r.id',
                //     'type' => 'string',
                //     'label' => 'Content',
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
                // 'neqn' => "!=",
                // 'ceq' => "<=>",
                'like' => "like",
                // 'likebin' => "like binary",
                // 'ntlike' => "not like",
                // 'ilike' => "ilike",
                // 'regex' => "regexp",
                // 'notregex' => "not regexp",
                // 'simto' => "similar to",
                // 'nsimto' => "not similar to",
                // 'nilike' => "not ilike",
            ],
            // 'css' => []

        ]);

        $view->with('datagrid', $datagrid);
        // $view->with('count', $this->users->count());
    }
}
