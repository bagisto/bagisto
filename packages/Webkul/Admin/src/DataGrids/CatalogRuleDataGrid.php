<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Catalog Rule DataGrid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('catalog_rules')
                ->addSelect('catalog_rules.id', 'name', 'status', 'starts_from', 'ends_till', 'sort_order');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'starts_from',
            'label'      => trans('admin::app.datagrid.start'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'ends_till',
            'label'      => trans('admin::app.datagrid.end'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'wrapper'    => function($value) {
                if ($value->status == 1) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => trans('admin::app.datagrid.priority'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => 'Edit Catalog Rule',
            'method' => 'GET',
            'route'  => 'admin.catalog-rules.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => 'Delete Catalog Rule',
            'method' => 'POST',
            'route'  => 'admin.catalog-rules.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }
}
