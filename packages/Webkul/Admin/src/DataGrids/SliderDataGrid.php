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
                    'alias' => 'sliderId',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 's.title',
                    'alias' => 'sliderTitle',
                    'type' => 'string',
                    'label' => 'title',
                    'sortable' => false
                ], [
                    'name' => 's.channel_id',
                    'alias' => 'channelId',
                    'type' => 'string',
                    'label' => 'Channel ID',
                    'sortable' => false,
                ], [
                    'name' => 'c.name',
                    'alias' => 'channelName',
                    'type' => 'string',
                    'label' => 'Channel Name',
                    'sortable' => true,
                ],
            ],

            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 's.id',
                    'alias' => 'sliderId',
                    'type' => 'number',
                    'label' => 'ID'
                ], [
                    'column' => 's.title',
                    'alias' => 'SliderTitle',
                    'type' => 'string',
                    'label' => 'Slider Title'
                ],
            ],

            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 's.id',
                    'type' => 'number',
                    'label' => 'ID'
                ], [
                    'column' => 's.title',
                    'type' => 'string',
                    'label' => 'Slider Title'
                ]
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