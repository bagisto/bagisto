<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Catalog Rule DataGrid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('catalog_rules')
                ->select('id')
                ->addSelect('id', 'name', 'starts_from', 'ends_till', 'priority', 'status', 'end_other_rules', 'action_type');

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
            'index' => 'name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'starts_from',
            'label' => trans('admin::app.datagrid.starts_from'),
            'type' => 'date',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'ends_till',
            'label' => trans('admin::app.datagrid.ends_till'),
            'type' => 'date',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'priority',
            'label' => trans('admin::app.datagrid.priority'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function ($value) {
                if ($value->status == 1)
                    return 'True';
                else
                    return 'False';
            }
        ]);

        $this->addColumn([
            'index' => 'end_other_rules',
            'label' => 'End Other Rules',
            'type' => 'boolean',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function ($value) {
                if ($value->end_other_rules == 1)
                    return 'True';
                else
                    return 'False';
            }
        ]);

        $this->addColumn([
            'index' => 'action_type',
            'label' => 'Action Type',
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'wrapper' => function ($value) {
                foreach(config('pricerules.catalog.actions') as $key => $action) {
                    if ($value->action_type == $key) {
                        return trans($action);
                    }
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET', //use post only for redirects only
            'route' => 'admin.catalog-rule.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        // $this->addAction([
        //     'type' => 'Delete',
        //     'method' => 'POST', //use post only for requests other than redirects
        //     'route' => 'admin.catalog-rule.delete',
        //     'icon' => 'icon trash-icon'
        // ]);
    }

    public function prepareMassActions()
    {
        // $this->addMassAction([
        //     'type' => 'delete',
        //     'action' => route('admin.catalog.attributes.massdelete'),
        //     'label' => 'Delete',
        //     'method' => 'DELETE'
        // ]);
    }
}
