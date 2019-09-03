<?php

namespace Webkul\SAASCustomizer\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Companies DataGrid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CompaniesDataGrid extends DataGrid
{
    protected $index = 'id'; //the column that needs to be treated as index column

    protected $sortOrder = 'desc'; //asc or desc

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('companies')
                ->select('id')
                ->addSelect('id', 'name', 'domain', 'is_active');

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
            'index' => 'domain',
            'label' => trans('saas::app.datagrid.domain'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'is_active',
            'label' => trans('admin::app.datagrid.status'),
            'type' => 'boolean',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ($row->is_active ==1) {
                    return '<i class="icon graph-up-icon"></i>';
                } else {
                    return '<i class="icon graph-down-icon"></i>';
                }
            }
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'method' => 'GET', //use post only for redirects only
            'route' => 'super.companies.show-stats',
            'icon' => 'icon eye-icon'
        ]);

        $this->addAction([
            'type' => 'View',
            'method' => 'GET', //use post only for redirects only
            'route' => 'super.companies.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);
    }
}
