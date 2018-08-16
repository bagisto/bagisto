<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Sliders DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class SliderDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var SliderDataGrid
     * for Sliders
     */

    public function createSliderDataGrid()
    {

            return DataGrid::make([
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

    }

    public function render()
    {
        return $this->createSliderDataGrid()->render();
    }
}