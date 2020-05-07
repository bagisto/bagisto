<?php

namespace Webkul\Bulkupload\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Order Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProfileDataGrid extends DataGrid
{
    /**
     * @var integer
     */
    protected $index = 'id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('bulkupload_dataflowprofile')
        ->leftJoin('attribute_families', 'bulkupload_dataflowprofile.attribute_family_id', '=', 'attribute_families.id')
        ->leftJoin('customers', 'bulkupload_dataflowprofile.seller_id', '=', 'customers.id')
        ->select('bulkupload_dataflowprofile.id',
        DB::raw('CONCAT(customers.first_name, " ", customers.last_name) as seller_id'),
        'bulkupload_dataflowprofile.profile_name', 'attribute_families.name', 'bulkupload_dataflowprofile.created_at');


        $this->addFilter('created_at', 'bulkupload_dataflowprofile.created_at');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
       $this->addColumn([
            'index' => 'profile_name',
            'label' => trans('bulkupload::app.shop.bulk-upload.profile-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('bulkupload::app.shop.bulk-upload.attribute-set-name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        // $this->addColumn([
        //     'index' => 'seller_id',
        //     'label' => 'Name',
        //     'type' => 'string',
        //     'searchable' => true,
        //     'sortable' => true,
        //     'filterable' => false,
        //     'wrapper' => function($value) {
        //         if (! $value->seller_id)
        //             return "admin";
        //         else
        //             return $value->seller_id;
        //     }
        // ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('bulkupload::app.shop.sellers.account.profile.date'),
            'type' => 'datetime',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'Edit',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'bulkupload.admin.profile.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'bulkupload.admin.profile.delete',
            // 'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'product']),
            'icon' => 'icon trash-icon'
        ]);

        $this->enableAction = true;
    }


    public function prepareMassActions()
    {
        $this->addMassAction([
            'type' => 'delete',
            'label' => 'Delete',
            'action' => route('bulkupload.admin.profile.massDelete'),
            'method' => 'DELETE'
        ]);
    }
}