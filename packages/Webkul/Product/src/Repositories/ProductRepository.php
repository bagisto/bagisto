<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Product\Helpers\Toolbar;

class ProductRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $productAttributeValueRepository
     * @param  \Webkul\Product\Repositories\ElasticSearchRepository  $elasticSearchRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected AttributeRepository $attributeRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository,
        protected ElasticSearchRepository $elasticSearchRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     * Create product.
     *
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        $typeInstance = app(config('product_types.' . $data['type'] . '.class'));

        $product = $typeInstance->create($data);

        return $product;
    }

    /**
     * Update product.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        $product = $this->findOrFail($id);

        $product = $product->getTypeInstance()->update($data, $id, $attribute);

        $product->refresh();

        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }

        return $product;
    }

    /**
     * Copy product.
     *
     * @param  int  $id
     * @return \Webkul\Product\Contracts\Product
     */
    public function copy($id)
    {
        $product = $this->with([
            'attribute_family',
            'categories',
            'customer_group_prices',
            'inventories',
            'inventory_sources',
        ])->findOrFail($id);

        if ($product->parent_id) {
            throw new \Exception(trans('admin::app.catalog.products.variant-already-exist-message'));
        }

        DB::beginTransaction();

        try {
            $copiedProduct = $product->getTypeInstance()->copy();
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            throw $e;
        }

        DB::commit();

        return $copiedProduct;
    }

    /**
     * Return product by filtering through attribute values.
     *
     * @param  string  $code
     * @param  mixed  $value
     * @return \Webkul\Product\Contracts\Product
     */
    public function findByAttributeCode($code, $value)
    {
        $attribute = $this->attributeRepository->findOneByField('code', $code);

        $attributeValues = $this->productAttributeValueRepository->findWhere([
            'attribute_id'          => $attribute->id,
            $attribute->column_name => $value,
        ]);

        if ($attribute->value_per_channel) {
            if ($attribute->value_per_locale) {
                $attributeValues = $attributeValues
                    ->where('channel', core()->getRequestedChannelCode())
                    ->where('locale', core()->getRequestedLocaleCode());
            } else {
                $attributeValues = $attributeValues
                    ->where('channel', core()->getRequestedChannelCode());
            }
        } else {
            if ($attribute->value_per_locale) {
                $attributeValues = $attributeValues
                    ->where('locale', core()->getRequestedLocaleCode());
            }
        }

        return $attributeValues->first()?->product;
    }

    /**
     * Retrieve product from slug without throwing an exception.
     *
     * @param  string  $slug
     * @return \Webkul\Product\Contracts\Product
     */
    public function findBySlug($slug)
    {
        return $this->findByAttributeCode('url_key', $slug);
    }

    /**
     * Retrieve product from slug.
     *
     * @param  string  $slug
     * @return \Webkul\Product\Contracts\Product
     */
    public function findBySlugOrFail($slug)
    {
        $product = $this->findByAttributeCode('url_key', $slug);

        if (! $product) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->model), $slug
            );
        }

        return $product;
    }

    /**
     * Get all products.
     *
     * @param  string  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function getAll($categoryId = null)
    {
        if (core()->getConfigData('catalog.products.storefront.search_mode') == 'elastic') {
            return $this->searchFromElastic($categoryId);
        } else {
            return $this->searchFromDatabase($categoryId);
        }
    }

    /**
     * Search product from database.
     *
     * @param  string  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function searchFromDatabase($categoryId)
    {
        $params = array_merge([
            'status'               => 1,
            'visible_individually' => 1,
            'url_key'              => null,
        ], request()->input());

        if (! empty($params['search'])) {
            $params['name'] = $params['search'];
        }

        $query = $this->with([
            'images',
            'videos',
            'attribute_values',
            'price_indices',
            'inventory_indices',
            'reviews',
        ])->scopeQuery(function ($query) use ($params, $categoryId) {
            $prefix = DB::getTablePrefix();

            $qb = $query->distinct()
                ->select('products.*')
                ->leftJoin('products as variants', DB::raw('COALESCE(' . $prefix . 'variants.parent_id, ' . $prefix . 'variants.id)'), '=', 'products.id')
                ->leftJoin('product_price_indices', function ($join) {
                    $customerGroup = $this->customerRepository->getCurrentGroup();

                    $join->on('products.id', '=', 'product_price_indices.product_id')
                        ->where('product_price_indices.customer_group_id', $customerGroup->id);
                });

            if ($categoryId) {
                $qb->leftJoin('product_categories', 'product_categories.product_id', '=', 'products.id')
                    ->whereIn('product_categories.category_id', explode(',', $categoryId));
            }

            if (! empty($params['type'])) {
                $qb->where('products.type', $params['type']);
            }

            /**
             * Filter query by price.
             */
            if (! empty($params['price'])) {
                $priceRange = explode(',', $params['price']);

                $qb->whereBetween('product_price_indices.min_price', [
                    core()->convertToBasePrice(current($priceRange)),
                    core()->convertToBasePrice(end($priceRange)),
                ]);
            }

            /**
             * Retrieve all the filterable attributes.
             */
            $filterableAttributes = $this->attributeRepository->getProductDefaultAttributes(array_keys($params));

            /**
             * Filter the required attributes.
             */
            $attributes = $filterableAttributes->whereIn('code', [
                'name',
                'status',
                'visible_individually',
                'url_key',
            ]);

            /**
             * Filter collection by required attributes.
             */
            foreach ($attributes as $attribute) {
                $alias = $attribute->code . '_product_attribute_values';

                $qb->leftJoin('product_attribute_values as ' . $alias, 'products.id', '=', $alias . '.product_id')
                    ->where($alias . '.attribute_id', $attribute->id);

                if ($attribute->code == 'name') {
                    $qb->where($alias . '.text_value', 'like', '%' . urldecode($params['name']) . '%');
                } elseif ($attribute->code == 'url_key') {
                    if (empty($params['url_key'])) {
                        $qb->whereNotNull($alias . '.text_value');
                    } else {
                        $qb->where($alias . '.text_value', 'like', '%' . urldecode($params['url_key']) . '%');
                    }
                } else {
                    $qb->where($alias . '.' . $attribute->column_name, 1);
                }
            }

            /**
             * Filter the filterable attributes.
             */
            $attributes = $filterableAttributes->whereNotIn('code', [
                'price',
                'name',
                'status',
                'visible_individually',
                'url_key',
            ]);

            /**
             * Filter query by attributes.
             */
            if ($attributes->isNotEmpty()) {
                $qb->leftJoin('product_attribute_values', 'products.id', '=', 'product_attribute_values.product_id');

                $qb->where(function ($filterQuery) use ($params, $attributes) {
                    foreach ($attributes as $attribute) {
                        $filterQuery->orWhere(function ($attributeQuery) use ($params, $attribute) {
                            $attributeQuery = $attributeQuery->where('product_attribute_values.attribute_id', $attribute->id);

                            $values = explode(',', $params[$attribute->code]);

                            if ($attribute->type == 'price') {
                                $attributeQuery->whereBetween('product_attribute_values.' . $attribute->column_name, [
                                    core()->convertToBasePrice(current($values)),
                                    core()->convertToBasePrice(end($values)),
                                ]);
                            } else {
                                $attributeQuery->whereIn('product_attribute_values.' . $attribute->column_name, $values);
                            }
                        });
                    }
                });

                /**
                 * This is key! if a product has been filtered down to the same number of attributes that we filtered on,
                 * we know that it has matched all of the requested filters.
                 *
                 * To Do (@devansh): Need to monitor this.
                 */
                $qb->groupBy('products.id');
                $qb->havingRaw('COUNT(*) = ' . count($attributes));
            }

            /**
             * Sort collection.
             */
            $sortOptions = $this->getSortOptions($params);

            if ($sortOptions['order'] != 'rand') {
                $attribute = $this->attributeRepository->findOneByField('code', $sortOptions['sort']);

                if ($attribute) {
                    if ($attribute->code === 'price') {
                        $qb->orderBy('product_price_indices.min_price', $sortOptions['order']);
                    } else {
                        $alias = 'sort_product_attribute_values';

                        $qb->leftJoin('product_attribute_values as ' . $alias, function ($join) use ($alias, $attribute) {
                            $join->on('products.id', '=', $alias . '.product_id')
                                ->where($alias . '.attribute_id', $attribute->id)
                                ->where($alias . '.channel', core()->getRequestedChannelCode())
                                ->where($alias . '.locale', core()->getRequestedLocaleCode());
                        })
                            ->orderBy($alias . '.' . $attribute->column_name, $sortOptions['order']);
                    }
                } else {
                    /* `created_at` is not an attribute so it will be in else case */
                    $qb->orderBy('products.created_at', $sortOptions['order']);
                }
            } else {
                return $qb->inRandomOrder();
            }

            return $qb->groupBy('products.id');
        });

        /**
         * Apply scope query so we can fetch the raw sql and perform a count.
         */
        $query->applyScope();

        $countQuery = clone $query->model;

        $count = collect(
            DB::select("select count(id) as aggregate from ({$countQuery->select('products.id')->reorder('products.id')->toSql()}) c",
            $countQuery->getBindings())
        )->pluck('aggregate')->first();

        $items = [];

        $limit = $this->getPerPageLimit($params);

        $currentPage = Paginator::resolveCurrentPage('page');

        if ($count > 0) {
            $query->scopeQuery(function ($query) use ($currentPage, $limit) {
                return $query->forPage($currentPage, $limit);
            });

            $items = $query->get();
        }

        return new LengthAwarePaginator($items, $count, $limit, $currentPage, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);
    }

    /**
     * Search product from elastic search
     *
     * @param  string  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function searchFromElastic($categoryId)
    {
        $params = request()->input();

        $currentPage = Paginator::resolveCurrentPage('page');

        $limit = $this->getPerPageLimit($params);

        $sortOptions = $this->getSortOptions($params);

        $indices = $this->elasticSearchRepository->search($categoryId, [
            'type'  => $params['type'] ?? '',
            'from'  => ($currentPage * $limit) - $limit,
            'limit' => $limit,
            'sort'  => $sortOptions['sort'],
            'order' => $sortOptions['order'],
        ]);

        $query = $this->with([
            'images',
            'videos',
            'attribute_values',
            'price_indices',
            'inventory_indices',
            'reviews',
        ])->scopeQuery(function ($query) use ($indices) {
            $qb = $query->distinct()
                ->whereIn('products.id', $indices['ids']);

            //Sort collection
            $qb->orderBy(DB::raw('FIELD(id, ' . implode(',', $indices['ids']) . ')'));

            return $qb;
        });

        $items = $indices['total'] ? $query->get() : [];

        $results = new LengthAwarePaginator($items, $indices['total'], $limit, $currentPage, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]
        );

        return $results;
    }

    /**
     * Products to show per page
     *
     * @param  array  $params
     * @return int
     */
    public function getPerPageLimit($params)
    {
        /**
         * Currently container binding not injecting in constructor.
         *
         * To Do (@devansh): Need a global helper or static helper
         * or a one place constant array to handle this.
         */
        $availableLimits = app(Toolbar::class)->getAvailableLimits();

        /**
         * Set a default value of 12 for the 'limit' parameter,
         * in case it is not provided or is not a valid integer.
         */
        $limit = (int) ($params['limit'] ?? 12);

        /**
         * If the 'limit' parameter is present but value not present
         * in available limits, use the default value of 12 instead.
         */
        $limit = in_array($limit, $availableLimits) ? $limit : 12;

        if ($productsPerPage = core()->getConfigData('catalog.products.storefront.products_per_page')) {
            $pages = explode(',', $productsPerPage);

            $limit = $params['limit'] ?? current($pages);
        }

        return $limit;
    }

    /**
     * Products to show per page
     *
     * @param  array  $params
     * @return array
     */
    public function getSortOptions($params)
    {
        $sortOptions = explode('-', core()->getConfigData('catalog.products.storefront.sort_by') ?: 'name-desc');

        return [
            'sort'  => $params['sort'] ?? current($sortOptions),
            'order' => $params['order'] ?? end($sortOptions),
        ];
    }

    /**
     * Returns product's super attribute with options.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getSuperAttributes($product)
    {
        $superAttributes = [];

        foreach ($product->super_attributes as $key => $attribute) {
            $superAttributes[$key] = $attribute->toArray();

            foreach ($attribute->options as $option) {
                $superAttributes[$key]['options'][] = [
                    'id'           => $option->id,
                    'admin_name'   => $option->admin_name,
                    'sort_order'   => $option->sort_order,
                    'swatch_value' => $option->swatch_value,
                ];
            }
        }

        return $superAttributes;
    }

    /**
     * Return category product maximum price.
     *
     * @param  int  $categoryId
     * @return float
     */
    public function getCategoryProductMaximumPrice($categoryId)
    {
        $customerGroup = $this->customerRepository->getCurrentGroup();

        return $this->model
            ->leftJoin('product_price_indices', 'products.id', 'product_price_indices.product_id')
            ->leftJoin('product_categories', 'products.id', 'product_categories.product_id')
            ->where('product_price_indices.customer_group_id', $customerGroup->id)
            ->where('product_categories.category_id', $categoryId)
            ->max('min_price');
    }
}
