<?php

namespace Webkul\Admin\Http\ViewComposers\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

// use App\Repositories\UserRepository;

class SliderComposer
{
    /**
     * The Data Grid implementation.
     *
     * @var CountryComposer
     * for countries
     */


    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $datagrid = DataGrid::make([
            'name' => 'Sliders',
            'table' => 'sliders',
            'select' => 'id',
            'perpage' => 5,
            'aliased' => false, //use this with false as default and true in case of joins

            'massoperations' =>[
                [
                    'route' => route('admin.datagrid.delete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ],
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to delete this record?',
                    'icon' => 'icon trash-icon',
                ],
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

            //use aliasing on secodary columns if join is performed

            'columns' => [

                [
                    'name' => 'id',
                    'alias' => 'slider_id',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'title',
                    'alias' => 'slider_title',
                    'type' => 'string',
                    'label' => 'title',
                    'sortable' => true,
                ],
            ],

            //don't use aliasing in case of filters

            'filterable' => [
                // [
                //     'column' => 'id',
                //     'alias' => 'locale_id',
                //     'type' => 'number',
                //     'label' => 'ID',
                // ],
                // [
                //     'column' => 'code',
                //     'alias' => 'locale_code',
                //     'type' => 'string',
                //     'label' => 'Code',
                // ],
                // [
                //     'column' => 'name',
                //     'alias' => 'locale_name',
                //     'type' => 'string',
                //     'label' => 'Name',
                // ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                // [
                //     'column' => 'name',
                //     'type' => 'string',
                //     'label' => 'Name',
                // ],
                // [
                //     'column' => 'code',
                //     'type' => 'string',
                //     'label' => 'Code',
                // ],
            ],

            //list of viable operators that will be used
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