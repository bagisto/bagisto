<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * AttributeDataGrid class
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeDataGrid extends DataGrid
{
    protected $index = 'id'; // column that needs to be treated as index column

    protected $sortOrder = 'desc'; // asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('attributes')
                ->select('id')
                ->addSelect('id', 'code', 'admin_name', 'type', 'is_required', 'is_unique', 'value_per_locale', 'value_per_channel');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'code',
            'label' => trans('admin::app.datagrid.code'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'admin_name',
            'label' => trans('admin::app.datagrid.admin-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'type',
            'label' => trans('admin::app.datagrid.type'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'is_required',
            'label' => trans('admin::app.datagrid.required'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'wrapper' => function($value) {
                if ($value->is_required == 1)
                    return 'True';
                else
                    return 'False';
            }
        ]);

        $this->addColumn([
            'index' => 'is_unique',
            'label' => trans('admin::app.datagrid.unique'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function($value) {
                if ($value->is_unique == 1)
                    return 'True';
                else
                    return 'False';
            }
        ]);

        $this->addColumn([
            'index' => 'value_per_locale',
            'label' => trans('admin::app.datagrid.per-locale'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function($value) {
                if ($value->value_per_locale == 1)
                    return 'True';
                else
                    return 'False';
            }
        ]);

        $this->addColumn([
            'index' => 'value_per_channel',
            'label' => trans('admin::app.datagrid.per-channel'),
            'type' => 'boolean',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'wrapper' => function($value) {
                if ($value->value_per_channel == 1)
                    return 'True';
                else
                    return 'False';
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET', //use post only for redirects only
            'route' => 'admin.catalog.attributes.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'POST', //use post only for requests other than redirects
            'route' => 'admin.catalog.attributes.delete',
            'icon' => 'icon trash-icon'
        ]);
    }

    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'action' => route('admin.catalog.attributes.massdelete'),
            'label' => 'Delete',
            'index' => 'admin_name',
            'method' => 'DELETE'
        ]);
    }
}
