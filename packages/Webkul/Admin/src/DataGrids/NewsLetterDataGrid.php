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
class NewsLetterDataGrid extends DataGrid
{
    public $allColumns = [];

    public function __construct() {
        $this->itemsPerPage = 5;
    }

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('subscribers_list')->addSelect('id', 'is_subscribed', 'email');

        $this->setQueryBuilder($queryBuilder);
    }

    public function setIndex() {
        $this->index = 'id';
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'alias' => 'subsId',
            'label' => 'ID',
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'width' => '40px'
        ]);

        $this->addColumn([
            'index' => 'is_subscribed',
            'alias' => 'subsCode',
            'label' => 'Subscribed',
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'width' => '100px',
            'wrapper' => function($value){
                if($value == 1)
                    return 'True';
                else
                    return 'False';
            }
        ]);

        $this->addColumn([
            'index' => 'email',
            'alias' => 'subsEmail',
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
            'route' => 'admin.customers.subscribers.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);

        $this->addAction([
            'type' => 'Delete',
            'route' => 'admin.customers.subscribers.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'Exchange Rate']),
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