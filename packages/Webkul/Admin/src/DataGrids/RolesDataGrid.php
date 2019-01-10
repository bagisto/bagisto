<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\AbsGrid;
use DB;

/**
 * News Letter Grid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class RolesDataGrid extends AbsGrid
{
    public $allColumns = [];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('roles')->select('id')->addSelect($this->columns);

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'column' => 'id',
            'alias' => 'roleId',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'column' => 'name',
            'alias' => 'roleName',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'column' => 'permission_type',
            'alias' => 'roleType',
            'label' => 'Permission Type',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->prepareAction([
            'type' => 'Edit',
            'route' => 'admin.roles.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);
    }

    public function prepareMassActions() {
        // $this->prepareMassAction([
        //     'type' => 'delete',
        //     'action' => route('admin.catalog.products.massdelete'),
        //     'method' => 'DELETE'
        // ]);

        // $this->prepareMassAction([
        //     'type' => 'update',
        //     'action' => route('admin.catalog.products.massupdate'),
        //     'method' => 'PUT',
        //     'options' => [
        //         0 => true,
        //         1 => false,
        //     ]
        // ]);
    }

    public function render()
    {
        $this->addColumns();

        $this->prepareActions();

        $this->prepareMassActions();

        $this->prepareQueryBuilder();

        return view('ui::testgrid.table')->with('results', ['records' => $this->getCollection(), 'columns' => $this->allColumns, 'actions' => $this->actions, 'massactions' => $this->massActions]);
    }
}