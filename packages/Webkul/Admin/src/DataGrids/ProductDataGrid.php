<?php

namespace Webkul\Admin\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\DataGrid\DataGrid;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Product\Repositories\ProductRepository;

class ProductDataGrid extends DataGrid
{
    /**
     * Set index columns, ex: id.
     *
     * @var string
     */
    protected $primaryColumn = 'product_id';

    public function __construct(
        protected ProductRepository $productRepository,
        protected InventorySourceRepository $inventorySourceRepository
    ) {
    }

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        if (core()->getRequestedChannelCode() === 'all') {
            $whereInChannels = Channel::query()->pluck('code')->toArray();
        } else {
            $whereInChannels = [core()->getRequestedChannelCode()];
        }

        if (core()->getRequestedLocaleCode() === 'all') {
            $whereInLocales = Locale::query()->pluck('code')->toArray();
        } else {
            $whereInLocales = [core()->getRequestedLocaleCode()];
        }

        /* query builder */
        $queryBuilder = DB::table('product_flat')
            ->leftJoin('attribute_families as af', 'product_flat.attribute_family_id', '=', 'af.id')
            ->leftJoin('product_inventories', 'product_flat.product_id', '=', 'product_inventories.product_id')
            ->leftJoin('product_images as images', 'product_flat.product_id', '=', 'images.product_id')
            ->distinct()
            ->leftJoin('product_categories as pc', 'product_flat.product_id', '=', 'pc.product_id')
            ->leftJoin('category_translations as ct', function ($leftJoin) use ($whereInLocales) {
                $leftJoin->on('pc.category_id', '=', 'ct.category_id')
                    ->whereIn('ct.locale', $whereInLocales);
            })
            ->select(
                'product_flat.locale',
                'product_flat.channel',
                'images.path as base_image',
                DB::raw('COUNT(DISTINCT images.id) as images_count'),
                'pc.category_id',
                'ct.name as category_name',
                'product_flat.product_id',
                'product_flat.sku as product_sku',
                'product_flat.product_number',
                'product_flat.name as product_name',
                'product_flat.type as product_type',
                'product_flat.status',
                'product_flat.price',
                'product_flat.url_key',
                'product_flat.visible_individually',
                'af.name as attribute_family',
                DB::raw('SUM(' . DB::getTablePrefix() . 'product_inventories.qty) as quantity')
            );

        $queryBuilder->groupBy(
            'product_flat.product_id',
            'product_flat.locale',
            'product_flat.channel'
        );

        $queryBuilder->whereIn('product_flat.locale', $whereInLocales);
        $queryBuilder->whereIn('product_flat.channel', $whereInChannels);

        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('product_name', 'product_flat.name');
        $this->addFilter('product_sku', 'product_flat.sku');
        $this->addFilter('product_number', 'product_flat.product_number');
        $this->addFilter('status', 'product_flat.status');
        $this->addFilter('product_type', 'product_flat.type');
        $this->addFilter('attribute_family', 'attribute_families.name');

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
            'index'      => 'product_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_sku',
            'label'      => trans('admin::app.datagrid.sku'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_number',
            'label'      => trans('admin::app.datagrid.product-number'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row) {
                if (
                    !empty($row->visible_individually)
                    && !empty($row->url_key)
                ) {
                    return "<a href='" . route('shop.product_or_category.index', $row->url_key) . "' target='_blank'>" . $row->product_name . '</a>';
                }

                return $row->product_name;
            },
        ]);

        $this->addColumn([
            'index'      => 'attribute_family',
            'label'      => trans('admin::app.datagrid.attribute-family'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_type',
            'label'      => trans('admin::app.datagrid.type'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
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
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'quantity',
            'label'      => trans('admin::app.datagrid.qty'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
            'closure'    => function ($row) {
                if (is_null($row->quantity)) {
                    return 0;
                }

                return $this->renderQuantityView($row);
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
            'icon'     => 'icon-edit',
            'title'    => trans('admin::app.datagrid.edit'),
            'method'   => 'GET',
            'url'      => function ($row) {
                return route('admin.catalog.products.edit', $row->product_id);
            },

            'condition' => function () {
                return true;
            },
        ]);

        $this->addAction([
            'icon'    => 'icon-delete',
            'title'   => trans('admin::app.datagrid.delete'),
            'method'  => 'DELETE',
            'url'     => function ($row) {
                return route('admin.catalog.products.delete', $row->product_id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-copy',
            'title'  => trans('admin::app.datagrid.copy'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.catalog.products.copy', $row->product_id);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'title'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.catalog.products.mass_delete'),
            'method' => 'POST',
        ]);

        $this->addMassAction([
            'title'   => trans('admin::app.datagrid.update-status'),
            'action'  => route('admin.catalog.products.mass_update'),
            'method'  => 'POST',
            'options' => [
                trans('admin::app.datagrid.active')    => 1,
                trans('admin::app.datagrid.inactive')  => 0,
            ],
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
