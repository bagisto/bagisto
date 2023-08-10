<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CartRuleDataGrid extends DataGrid
{
 
    /**
     * Customer group.
     *
     * @var string
     */
    protected $customer_group = 'all';

    /**
     * Channel.
     *
     * @var string
     */
    protected $channel = 'all';

    /**
     * Create a new datagrid instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->customer_group = core()->getRequestedCustomerGroupCode() ?? 'all';

        $this->channel = core()->getRequestedChannelCode(false) ?? 'all';
    }

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('cart_rules')
            ->leftJoin('cart_rule_coupons', function ($leftJoin) {
                $leftJoin->on('cart_rule_coupons.cart_rule_id', '=', 'cart_rules.id')
                    ->where('cart_rule_coupons.is_primary', 1);
            })
            ->addSelect(
                'cart_rules.id',
                'name',
                'cart_rule_coupons.code as coupon_code',
                'status',
                'starts_from',
                'ends_till',
                'sort_order'
            );

        // $this->addFilter('id', 'cart_rules.id');
        // $this->addFilter('coupon_code', 'cart_rule_coupons.code');

        if ($this->customer_group !== 'all') {
            $queryBuilder->leftJoin(
                'cart_rule_customer_groups',
                'cart_rule_customer_groups.cart_rule_id',
                '=',
                'cart_rules.id'
            );

            $queryBuilder->where('cart_rule_customer_groups.customer_group_id', $this->customer_group);
        }

        if ($this->channel !== 'all') {
            $queryBuilder->leftJoin(
                'cart_rule_channels',
                'cart_rule_channels.cart_rule_id',
                '=',
                'cart_rules.id'
            );

            $queryBuilder->where('cart_rule_channels.channel_id', $this->channel);
        }

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'coupon_code',
            'label'      => trans('admin::app.datagrid.coupon-code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                return $value->coupon_code ?? '-';
            },
        ]);

        $this->addColumn([
            'index'      => 'starts_from',
            'label'      => trans('admin::app.datagrid.start'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($value) {
                return $value->starts_from ?? '-';
            },
        ]);

        $this->addColumn([
            'index'      => 'ends_till',
            'label'      => trans('admin::app.datagrid.end'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($value) {
                return $value->ends_till ?? '-';
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.status'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status == 1) {
                    return trans('admin::app.datagrid.active');
                } elseif ($value->status == 0) {
                    return trans('admin::app.datagrid.inactive');
                }

                return trans('admin::app.datagrid.draft');
            },
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => trans('admin::app.datagrid.priority'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.cart_rules.edit',
            'url'    => function ($row) {
                return route('admin.cart_rules.edit', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.copy'),
            'method' => 'GET',
            'route'  => 'admin.cart_rules.copy',
            'url'    => function ($row) {
                return route('admin.cart_rules.copy', $row->id);
            },
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'DELETE',
            'route'  => 'admin.cart_rules.delete',
            'url'    => function ($row) {
                return route('admin.cart_rules.delete', $row->id);
            },
        ]);
    }
}
