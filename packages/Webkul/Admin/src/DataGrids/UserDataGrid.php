<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * News Letter Grid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class UserDataGrid extends DataGrid
{
    public $allColumns = [];

    public function __construct() {
        $this->itemsPerPage = 5;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('admins as u')->addSelect('u.id', 'u.name', 'u.status', 'u.email', 'ro.name')->leftJoin('roles as ro', 'u.role_id', '=', 'ro.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function setIndex() {
        $this->index = 'id';
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'u.id',
            'alias' => 'adminId',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'u.name',
            'alias' => 'adminName',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'u.status',
            'alias' => 'adminStatus',
            'label' => 'Status',
            'type' => 'boolean',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
            'wrapper' => function($value) {
                if($value == 1) {
                    return 'Active';
                } else {
                    return 'Inactive';
                }
            }
        ]);

        $this->addColumn([
            'index' => 'u.email',
            'alias' => 'adminEmail',
            'label' => 'Email',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->addAction([
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
}