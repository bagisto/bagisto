<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * NewsLetterDataGrid Class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class NewsLetterDataGrid extends DataGrid
{
    protected $paginate = true;

    protected $itemsPerPage = 5; //overriding the default items per page

    protected $index = 'id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('subscribers_list')->addSelect('id', 'is_subscribed', 'email');

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
        ]);

        $this->addColumn([
            'index' => 'is_subscribed',
            'label' => trans('admin::app.datagrid.subscribed'),
            'type' => 'string',
            'searchable' => false,
            'sortable' => true,
            'wrapper' => function($value) {
                if ($value->is_subscribed == 1)
                    return 'True';
                else
                    return 'False';
            }
        ]);

        $this->addColumn([
            'index' => 'email',
            'label' => trans('admin::app.datagrid.email'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
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