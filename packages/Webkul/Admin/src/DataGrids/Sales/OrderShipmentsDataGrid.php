<?php

namespace Webkul\Admin\DataGrids\Sales;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Sales\Models\OrderAddress;

class OrderShipmentsDataGrid extends DataGrid
{
    /**
     * Shipment Id.
     *
     * @var string
     */
    protected $primaryColumn = 'shipment_id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('shipments')
            ->leftJoin('addresses as order_address_shipping', function ($leftJoin) {
                $leftJoin->on('order_address_shipping.order_id', '=', 'shipments.order_id')
                    ->where('order_address_shipping.address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
            })
            ->leftJoin('orders as ors', 'shipments.order_id', '=', 'ors.id')
            ->leftJoin('inventory_sources as is', 'shipments.inventory_source_id', '=', 'is.id')
            ->select(
                'shipments.id as shipment_id',
                'ors.increment_id as shipment_order_id',
                'shipments.total_qty as shipment_total_qty',
                'ors.created_at as order_date',
                'shipments.created_at as shipment_created_at'
            )
            ->addSelect(DB::raw('CONCAT('.DB::getTablePrefix().'order_address_shipping.first_name, " ", '.DB::getTablePrefix().'order_address_shipping.last_name) as shipped_to'))
            ->selectRaw('IF('.DB::getTablePrefix().'shipments.inventory_source_id IS NOT NULL,'.DB::getTablePrefix().'is.name, '.DB::getTablePrefix().'shipments.inventory_source_name) as inventory_source_name');

        $this->addFilter('shipment_id', 'shipments.id');
        $this->addFilter('shipment_order_id', 'ors.increment_id');
        $this->addFilter('shipment_total_qty', 'shipments.total_qty');
        // $this->addFilter('inventory_source_name', DB::raw('IF(' . DB::getTablePrefix() . 'shipments.inventory_source_id IS NOT NULL,' . DB::getTablePrefix() . 'is.name, ' . DB::getTablePrefix() . 'shipments.inventory_source_name)'));
        $this->addFilter('order_date', 'ors.created_at');
        $this->addFilter('shipment_created_at', 'shipments.created_at');
        // $this->addFilter('shipped_to', DB::raw('CONCAT(' . DB::getTablePrefix() . 'order_address_shipping.first_name, " ", ' . DB::getTablePrefix() . 'order_address_shipping.last_name)'));

        return $queryBuilder;
    }

    /**
     * Add Columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'shipment_id',
            'label'      => trans('admin::app.sales.shipments.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'shipment_order_id',
            'label'      => trans('admin::app.sales.shipments.index.datagrid.order-id'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'shipment_total_qty',
            'label'      => trans('admin::app.sales.shipments.index.datagrid.total-qty'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'inventory_source_name',
            'label'      => trans('admin::app.sales.shipments.index.datagrid.inventory-source'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'shipped_to',
            'label'      => trans('admin::app.sales.shipments.index.datagrid.shipment-to'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'order_date',
            'label'      => trans('admin::app.sales.shipments.index.datagrid.order-date'),
            'type'       => 'date_range',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'shipment_created_at',
            'label'      => trans('admin::app.sales.shipments.index.datagrid.shipment-date'),
            'type'       => 'date_range',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('sales.shipments.view')) {
            $this->addAction([
                'icon'   => 'icon-view',
                'title'  => trans('admin::app.sales.shipments.index.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.shipments.view', $row->shipment_id);
                },
            ]);
        }
    }
}
