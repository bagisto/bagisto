<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\AbsGrid;
use DB;

/**
 * Currency Data Grid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerGroupDataGrid extends AbsGrid
{
    public $allColumns = [];

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customer_groups')->addSelect('id', 'name');

        $this->setQueryBuilder($queryBuilder);
    }

    public function setIndex() {
        $this->index = 'id';
    }

    public function addColumns()
    {
        $this->addColumn([
            'column' => 'id',
            'alias' => 'groupId',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            // 'column' => 'CONCAT(cus.first_name, " ", cus.last_name)',
            'column' => 'name',
            'alias' => 'groupName',
            'label' => 'Name',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'route' => 'admin.customer.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'route' => 'admin.customer.delete',
            'icon' => 'icon trash-icon'
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