<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\View\View;
use Webkul\Ui\DataGrid\Facades\DataGrid;

/**
 * Attributes DataGrid
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class AttributeDataGrid
{
    /**
     * The Data Grid implementation.
     *
     * @var AttributeDataGrid
     * for countries
     */

    public function createAttributeDataGrid()
    {

            return DataGrid::make([
            'name' => 'Attributes',
            'table' => 'attributes',
            'select' => 'id',
            'perpage' => 10,
            'aliased' => true,

            'massoperations' => [
                [
                    'route' => route('admin.catalog.attributes.massdelete'),
                    'method' => 'DELETE',
                    'label' => 'Delete',
                    'type' => 'button',
                ]
            ],

            'actions' => [
                [
                    'type' => 'Edit',
                    'route' => 'admin.catalog.attributes.edit',
                    'confirm_text' => 'Do you really want to edit this record?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => 'admin.catalog.attributes.delete',
                    'confirm_text' => 'Do you really want to delete this record?',
                    'icon' => 'icon trash-icon',
                ],
            ],

            'join' => [],

            //use aliasing on secodary columns if join is performed
            'columns' => [
                [
                    'name' => 'id',
                    'alias' => 'attributeId',
                    'type' => 'number',
                    'label' => 'ID',
                    'sortable' => true,
                ], [
                    'name' => 'code',
                    'alias' => 'attributeCode',
                    'type' => 'string',
                    'label' => 'Code',
                    'sortable' => true,
                ], [
                    'name' => 'admin_name',
                    'alias' => 'attributeAdminName',
                    'type' => 'string',
                    'label' => 'Name',
                    'sortable' => true,
                ], [
                    'name' => 'type',
                    'alias' => 'attributeType',
                    'type' => 'string',
                    'label' => 'Type',
                    'sortable' => true,
                ], [
                    'name' => 'is_required',
                    'alias' => 'attributeIsRequired',
                    'type' => 'string',
                    'label' => 'Required',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 0)
                            return "False";
                        else
                            return "True";
                    },
                ], [
                    'name' => 'is_unique',
                    'alias' => 'attributeIsUnique',
                    'type' => 'string',
                    'label' => 'Unique',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 0)
                            return "False";
                        else
                            return "True";
                    },
                ], [
                    'name' => 'value_per_locale',
                    'alias' => 'attributeValuePerLocale',
                    'type' => 'string',
                    'label' => 'Locale based',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 0)
                            return "False";
                        else
                            return "True";
                    },
                ], [
                    'name' => 'value_per_channel',
                    'alias' => 'attributeValuePerChannel',
                    'type' => 'string',
                    'label' => 'Channel based',
                    'sortable' => true,
                    'wrapper' => function ($value) {
                        if($value == 0)
                            return "False";
                        else
                            return "True";
                    },
                ],
            ],

            'filterable' => [
                [
                    'column' => 'id',
                    'alias' => 'attributeId',
                    'type' => 'number',
                    'label' => 'ID',
                ], [
                    'column' => 'code',
                    'alias' => 'attributeCode',
                    'type' => 'string',
                    'label' => 'Code',
                ], [
                    'column' => 'admin_name',
                    'alias' => 'attributeAdminName',
                    'type' => 'string',
                    'label' => 'Name',
                ], [
                    'column' => 'type',
                    'alias' => 'attributeType',
                    'type' => 'string',
                    'label' => 'Type',
                ],
            ],

            //don't use aliasing in case of searchables
            'searchable' => [
                [
                    'column' => 'code',
                    'alias' => 'attributeCode',
                    'type' => 'string',
                ], [
                    'column' => 'admin_name',
                    'alias' => 'attributeAdminName',
                    'type' => 'string',
                ], [
                    'column' => 'type',
                    'alias' => 'attributeType',
                    'type' => 'string',
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
        return $this->createAttributeDataGrid()->render();
    }
}