<?php

namespace Webkul\Admin\DataGrids;

use Webkul\Ui\DataGrid\DataGrid;
use Illuminate\Support\Facades\DB;

class CartRuleDataGrid extends DataGrid
{
    /**
     * Set index columns, ex: id.
     *
     * @var string
     */
    protected $index = 'id';

    /**
     * Default sort order of datagrid.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

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
     * Contains the keys for which extra filters to show.
     *
     * @var string[]
     */
    protected $extraFilters = [
        'channels',
        'customer_groups',
    ];

    /**
     * Create a new datagrid instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

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

        $this->addFilter('id', 'cart_rules.id');
        $this->addFilter('coupon_code', 'cart_rule_coupons.code');

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

        $this->addFilter('status', 'status');

        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * Add columns.
     *
     * @return void
     */
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
        ]);

        $this->addColumn([
            'index'      => 'starts_from',
            'label'      => trans('admin::app.datagrid.start'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'ends_till',
            'label'      => trans('admin::app.datagrid.end'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
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
                } else if ($value->status == 0) {
                    return trans('admin::app.datagrid.inactive');
                } else {
                    return trans('admin::app.datagrid.draft');
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'sort_order',
            'label'      => trans('admin::app.datagrid.priority'),
            'type'       => 'number',
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
            'route'  => 'admin.cart-rules.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.copy'),
            'method' => 'GET',
            'route'  => 'admin.cart-rules.copy',
            'icon'   => 'icon copy-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'method' => 'POST',
            'route'  => 'admin.cart-rules.delete',
            'icon'   => 'icon trash-icon',
        ]);
    }
}
