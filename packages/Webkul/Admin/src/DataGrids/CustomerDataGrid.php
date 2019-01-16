<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * CustomerDataGrid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CustomerDataGrid extends DataGrid
{
    protected $itemsPerPage = 5;

    protected $index = 'customer_id'; //the column that needs to be treated as index column

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('customers as custs')->addSelect('custs.id as customer_id', 'custs.email as customer_email', 'cg.name as cust_grp_name')->addSelect(DB::raw('CONCAT(custs.first_name, " ", custs.last_name) as full_name'))->leftJoin('customer_groups as cg', 'custs.customer_group_id', '=', 'cg.id');

        $this->addFilters('customer_id', 'custs.id');
        $this->addFilters('customer_email', 'custs.email');
        $this->addFilters('cust_grp_name', 'cg.name');
        $this->addFilters('full_name', DB::raw('CONCAT(custs.first_name, " ", custs.last_name)'));

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'customer_id',
            'label' => trans('admin::app.datagrid.id'),
            'type' => 'number',
            'searchable' => true,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'full_name',
            'label' => trans('admin::app.datagrid.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'customer_email',
            'label' => trans('admin::app.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px'
        ]);

        $this->addColumn([
            'index' => 'cust_grp_name',
            'label' => trans('admin::app.datagrid.group'),
            'type' => 'string',
            'searchable' => false,
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