<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;


/**
 * Channels DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ChannelDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var CountryComposer
     * for countries
     */

    public function createChannelsDataGrid()
    {

            return DataGrid::make([
            'name' => 'Channels',
            'table' => 'channels',
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
                    'alias' => 'channel_id',
                    'type' => 'number',
                    'label' => 'Channel ID',
                    'sortable' => true,
                ],
                [
                    'name' => 'code',
                    'alias' => 'channel_code',
                    'type' => 'string',
                    'label' => 'Channel Code',
                    'sortable' => true,
                ],
                [
                    'name' => 'name',
                    'alias' => 'channel_name',
                    'type' => 'string',
                    'label' => 'Channel Name',
                    'sortable' => true,
                ],

            ],

            //don't use aliasing in case of filters

            'filterable' => [
                [
                    'column' => 'id',
                    'alias' => 'channel_id',
                    'type' => 'number',
                    'label' => 'Channel ID',
                ],
                [
                    'column' => 'code',
                    'alias' => 'channel_code',
                    'type' => 'string',
                    'label' => 'Channel Code',
                ],
                [
                    'column' => 'name',
                    'alias' => 'channel_name',
                    'type' => 'string',
                    'label' => 'Channel Name',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Channel Name',
                ],
                [
                    'column' => 'code',
                    'type' => 'string',
                    'label' => 'Channel Code',
                ],
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
        return $this->createChannelsDataGrid()->render();
    }
}