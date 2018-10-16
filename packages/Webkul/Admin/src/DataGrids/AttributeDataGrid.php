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
            'perpage' => 5,
            'aliased' => true,

            'massoperations' => [
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
                    'confirm_text' => 'Do you really want to do this?',
                    'icon' => 'icon pencil-lg-icon',
                ], [
                    'type' => 'Delete',
                    'route' => route('admin.datagrid.delete'),
                    'confirm_text' => 'Do you really want to do this?',
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
                ],
                [
                    'name' => 'code',
                    'alias' => 'attributeCode',
                    'type' => 'string',
                    'label' => 'Code',
                    'sortable' => true,
                ],
                [
                    'name' => 'admin_name',
                    'alias' => 'attributeAdminName',
                    'type' => 'string',
                    'label' => 'AdminName',
                    'sortable' => true,
                ],
                [
                    'name' => 'type',
                    'alias' => 'attributeType',
                    'type' => 'string',
                    'label' => 'Type',
                    'sortable' => true,
                ],
                [
                    'name' => 'is_required',
                    'alias' => 'attributeIsRequired',
                    'type' => 'string',
                    'label' => 'Required',
                    'sortable' => true,
                ],
                [
                    'name' => 'is_unique',
                    'alias' => 'attributeIsUnique',
                    'type' => 'string',
                    'label' => 'Unique',
                    'sortable' => true,
                ],
                [
                    'name' => 'value_per_locale',
                    'alias' => 'attributeValuePerLocale',
                    'type' => 'string',
                    'label' => 'ValuePerLocale',
                    'sortable' => true,
                ],
                [
                    'name' => 'value_per_channel',
                    'alias' => 'attributeValuePerChannel',
                    'type' => 'string',
                    'label' => 'ValuePerChannel',
                    'sortable' => true,
                ],

            ],

            'filterable' => [
                [
                    'column' => 'id',
                    'alias' => 'attributeId',
                    'type' => 'number',
                    'label' => 'ID',
                ],
                [
                    'column' => 'code',
                    'alias' => 'attributeCode',
                    'type' => 'string',
                    'label' => 'Code',
                ],
                [
                    'column' => 'admin_name',
                    'alias' => 'attributeAdminName',
                    'type' => 'string',
                    'label' => 'AdminName',
                ],
                [
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
                ],
                [
                    'column' => 'admin_name',
                    'alias' => 'attributeAdminName',
                    'type' => 'string',
                ],
                [
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