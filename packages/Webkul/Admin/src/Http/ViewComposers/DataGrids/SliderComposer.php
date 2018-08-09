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
            'table' => 'sliders as s',
            'select' => 's.id',
            'perpage' => 5,
            'aliased' => true, //use this with false as default and true in case of joins

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
                [
                    'join' => 'leftjoin',
                    'table' => 'channels as c',
                    'primaryKey' => 's.channel_id',
                    'condition' => '=',
                    'secondaryKey' => 'c.id',
                ]
            ],

            //use aliasing on secodary columns if join is performed

            'columns' => [

                [
                    'name' => 's.id',
                    'alias' => 'slider_id',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ],
                [
                    'name' => 's.title',
                    'alias' => 'slider_title',
                    'type' => 'string',
                    'label' => 'title',
                ],
                [
                    'name' => 's.channel_id',
                    'alias' => 'channel_id',
                    'type' => 'string',
                    'label' => 'Channel ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'c.name',
                    'alias' => 'channel_name',
                    'type' => 'string',
                    'label' => 'Channel Name',
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