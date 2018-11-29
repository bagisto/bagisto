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
            'perpage' => 10,
            'aliased' => false, //use this with false as default and true in case of joins

            'massoperations' =>[
                // [
                //     'route' => route('admin.datagrid.delete'),
                //     'method' => 'DELETE',
                //     'label' => 'Delete',
                //     'type' => 'button',
                // ],
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to edit this record?',
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
                    'alias' => 'channelID',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'code',
                    'alias' => 'channelCode',
                    'type' => 'string',
                    'label' => 'Code',
                    'sortable' => true,
                ], [
                    'name' => 'name',
                    'alias' => 'channelName',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                ], [
                    'name' => 'hostname',
                    'alias' => 'channelHostName',
                    'type' => 'string',
                    'label' => 'Host Name',
                    'sortable' => true,
                ],
            ],

            //don't use aliasing in case of filters
            'filterable' => [
                [
                    'column' => 'id',
                    'alias' => 'channelID',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'code',
                    'alias' => 'channelCode',
                    'type' => 'string',
                    'label' => 'Code',
                ], [
                    'column' => 'name',
                    'alias' => 'channelName',
                    'type' => 'string',
                    'label' => 'Name',
                ],
            ],

            //don't use aliasing in case of searchables

            'searchable' => [
                [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Name',
                ], [
                    'column' => 'code',
                    'type' => 'string',
                    'label' => 'Code',
                ], [
                    'column' => 'hostname',
                    'type' => 'string',
                    'label' => 'Host Name',
                ], [
                    'column' => 'name',
                    'type' => 'string',
                    'label' => 'Name',
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