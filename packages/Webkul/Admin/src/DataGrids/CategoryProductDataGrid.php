<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\Ui\DataGrid\DataGrid;

use Webkul\Product\Repositories\ProductRepository;
use Webkul\Inventory\Repositories\InventorySourceRepository;

class CategoryProductDataGrid extends DataGrid
{
    /**
     * Default sort order of datagrid.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Set index columns, ex: id.
     *
     * @var string
     */
    protected $index = 'product_id';

    /**
     * If paginated then value of pagination.
     *
     * @var int
     */
    protected $itemsPerPage = 10;

    /**
     * Locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * Channel.
     *
     * @var string
     */
    protected $channel;

    /**
     * Create datagrid instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Inventory\Repositories\InventorySourceRepository  $inventorySourceRepository
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected InventorySourceRepository $inventorySourceRepository
    )
    {
        /* locale */
        $this->locale = core()->getRequestedLocaleCode();

        /* channel */
        $this->channel = core()->getRequestedChannelCode();

        /* parent constructor */
        parent::__construct();
    }

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        /* query builder */
        $queryBuilder = DB::table('product_flat')
            ->leftJoin('attribute_families', 'product_flat.attribute_family_id', '=', 'attribute_families.id')
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->leftJoin('product_inventories', 'product_flat.product_id', '=', 'product_inventories.product_id')
            ->select(
                'product_flat.locale',
                'product_flat.channel',
                'product_flat.product_id',
                'products.sku as product_sku',
                'product_flat.product_number',
                'product_flat.name as product_name',
                'products.type as product_type',
                'product_flat.status',
                'product_flat.price',
                'attribute_families.name as attribute_family',
                DB::raw('SUM(' . DB::getTablePrefix() . 'product_inventories.qty) as quantity')
            );

        $queryBuilder->groupBy('product_flat.product_id', 'product_flat.locale', 'product_flat.channel');

        $queryBuilder->where('product_categories.category_id', request('id'));
        $queryBuilder->whereIn('product_flat.locale', [$this->locale]);
        $queryBuilder->whereIn('product_flat.channel', [$this->channel]);

        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('product_name', 'product_flat.name');
        $this->addFilter('product_sku', 'products.sku');
        $this->addFilter('product_number', 'product_flat.product_number');
        $this->addFilter('status', 'product_flat.status');
        $this->addFilter('product_type', 'products.type');

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
            'index'      => 'product_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_sku',
            'label'      => trans('admin::app.datagrid.sku'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_number',
            'label'      => trans('admin::app.datagrid.product-number'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if (! empty($row->visible_individually) && ! empty($row->url_key)) {
                    return "<a href='" . route('shop.productOrCategory.index', $row->url_key) . "' target='_blank'>" . $row->product_name . "</a>";
                }

                return $row->product_name;
            },
        ]);

        $this->addColumn([
            'index'      => 'attribute_family',
            'label'      => trans('admin::app.datagrid.attribute-family'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_type',
            'label'      => trans('admin::app.datagrid.type'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($value) {
                $html = '';

                if ($value->status) {
                    $html .= '<span class="badge badge-md badge-success">' . trans('admin::app.datagrid.active') . '</span>';
                } else {
                    $html .= '<span class="badge badge-md badge-danger">' . trans('admin::app.datagrid.inactive') . '</span>';
                }

                return $html;
            },
        ]);

        $this->addColumn([
            'index'      => 'price',
            'label'      => trans('admin::app.datagrid.price'),
            'type'       => 'price',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'quantity',
            'label'      => trans('admin::app.datagrid.qty'),
            'type'       => 'number',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
            'closure'    => function ($row) {
                if (is_null($row->quantity)) {
                    return 0;
                } else {
                    return $this->renderQuantityView($row);
                }
            },
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
            'title'     => trans('admin::app.datagrid.edit'),
            'method'    => 'GET',
            'route'     => 'admin.catalog.products.edit',
            'icon'      => 'icon pencil-lg-icon',
            'condition' => function () {
                return true;
            },
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.catalog.products.delete',
            'confirm_text' => trans('ui::app.datagrid.mass-action.delete', ['resource' => 'product']),
            'icon'         => 'icon trash-icon',
        ]);

        $this->addAction([
            'title'  => trans('admin::app.datagrid.copy'),
            'method' => 'GET',
            'route'  => 'admin.catalog.products.copy',
            'icon'   => 'icon copy-icon',
        ]);
    }

    /**
     * Render quantity view.
     *
     * @param  object  $row
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    private function renderQuantityView($row)
    {
        $product = $this->productRepository->find($row->product_id);

        $inventorySources = $this->inventorySourceRepository->findWhere(['status' => 1]);

        $totalQuantity = $row->quantity;

        return view('admin::catalog.products.datagrid.quantity', compact('product', 'inventorySources', 'totalQuantity'))->render();
    }
}
