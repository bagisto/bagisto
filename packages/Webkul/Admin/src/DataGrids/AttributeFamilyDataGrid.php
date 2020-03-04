<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * AttributeFamilyDataGrid Class
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeFamilyDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('attribute_families')->select('id')->addSelect('id', 'code', 'name');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('admin::app.code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'title'  => 'Edit Attribute Family',
            'method' => 'GET',
            'route'  => 'admin.catalog.families.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => 'Delete Attribute Family',
            'method' => 'POST',
            'route'  => 'admin.catalog.families.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }
}