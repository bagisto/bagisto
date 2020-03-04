<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use DB;

/**
 * Cart Rule DataGrid class
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleCouponsDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart_rules')
                ->select('id')
                ->addSelect('id', 'code', 'limit', 'usage_per_customer', 'usage_throttle');

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
            'index'      => 'code',
            'label'      => trans('admin::app.datagrid.code'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'limit',
            'label'      => trans('admin::app.datagrid.limit'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'limit',
            'label'      => trans('admin::app.datagrid.limit'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'usage_per_customer',
            'label'      => trans('admin::app.datagrid.usage-per-customer'),
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'wrapper'    => function($value) {
                if ($value->end_other_rules == 1) {
                    return 'true';
                } else {
                    return 'false';
                }
            },
        ]);
    }
}
