<?php

namespace Webkul\Admin\DataGrids\Catalog;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;
use Webkul\DataGrid\DataGrid;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Product\Repositories\ProductRepository;

class ProductDataGrid extends DataGrid
{
    /**
     * Constructor for the class
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Inventory\Repositories\InventorySourceRepository  $inventorySourceRepository
     * @return void
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

        /**
         * Query Builder to fetch records from `product_flat` table
         */
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
                'pc.category_id',
                'ct.name as category_name',
                'product_flat.product_id',
                'product_flat.sku',
                'product_flat.name',
                'product_flat.type',
                'product_flat.status',
                'product_flat.price',
                'product_flat.url_key',
                'product_flat.visible_individually',
                'af.name as attribute_family',
                DB::raw('SUM(' . DB::getTablePrefix() . 'product_inventories.qty) as quantity')
            )
            ->addSelect(DB::raw('COUNT(DISTINCT images.id) as images_count'));

        $queryBuilder->groupBy(
            'product_flat.product_id',
            'product_flat.locale',
            'product_flat.channel'
        );

        $queryBuilder->whereIn('product_flat.locale', $whereInLocales);
        $queryBuilder->whereIn('product_flat.channel', $whereInChannels);

        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('name', 'product_flat.name');
        $this->addFilter('type', 'product_flat.type');
        $this->addFilter('status', 'product_flat.status');
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
            'index'      => 'name',
            'label'      => trans('admin::app.catalog.products.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'sku',
            'label'      => trans('admin::app.catalog.products.index.datagrid.sku'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'attribute_family',
            'label'      => trans('admin::app.catalog.products.index.datagrid.attribute-family'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'base_image',
            'label'      => trans('admin::app.catalog.products.index.datagrid.image'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'price',
            'label'      => trans('admin::app.catalog.products.index.datagrid.price'),
            'type'       => 'price',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'quantity',
            'label'      => trans('admin::app.catalog.products.index.datagrid.qty'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_id',
            'label'      => trans('admin::app.catalog.products.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.catalog.products.index.datagrid.status'),
            'type'       => 'boolean',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'category_name',
            'label'      => trans('admin::app.catalog.products.index.datagrid.category'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('admin::app.catalog.products.index.datagrid.type'),
            'type'       => 'string',
            'searchable' => true,
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
        $this->addAction([
            'icon'     => 'icon-edit',
            'title'    => trans('admin::app.catalog.products.index.datagrid.edit'),
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
            'title'   => trans('admin::app.catalog.products.index.datagrid.delete'),
            'method'  => 'DELETE',
            'url'     => function ($row) {
                return route('admin.catalog.products.delete', $row->product_id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-copy',
            'title'  => trans('admin::app.catalog.products.index.datagrid.copy'),
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
            'title'  => trans('admin::app.catalog.products.index.datagrid.delete'),
            'url'    => route('admin.catalog.products.mass_delete'),
            'method' => 'POST',
        ]);

        $this->addMassAction([
            'title'   => trans('admin::app.catalog.products.index.datagrid.update-status'),
            'url'     => route('admin.catalog.products.mass_update'),
            'method'  => 'POST',
            'options' => [
                [
                    'name' => trans('admin::app.catalog.products.index.datagrid.active'),
                    'value' => 1,
                ],
                [
                    'name' => trans('admin::app.catalog.products.index.datagrid.disable'),
                    'value' => 0,
                ],
            ],
        ]);
    }
}
