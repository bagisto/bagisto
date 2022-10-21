<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Core\Eloquent\Repository;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Attribute\Repositories\AttributeRepository;

class ProductRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @param  \Webkul\Product\Repositories\ElasticSearchRepository  $elasticSearchRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected AttributeRepository $attributeRepository,
        protected ProductFlatRepository $productFlatRepository,
        protected ElasticSearchRepository $elasticSearchRepository,
        Container $container
    )
    {
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
     * Retrieve product from slug without throwing an exception.
     *
     * @param  string  $slug
     * @return \Webkul\Product\Contracts\ProductFlat
     */
    public function findBySlug($slug)
    {
        return $this->productFlatRepository->findOneWhere([
            'url_key' => $slug,
            'locale'  => app()->getLocale(),
            'channel' => core()->getCurrentChannelCode(),
        ]);
    }

    /**
     * Retrieve product from slug.
     *
     * @param  string  $slug
     * @param  string  $columns
     * @return \Webkul\Product\Contracts\Product
     */
    public function findBySlugOrFail($slug, $columns = null)
    {
        $product = $this->productFlatRepository->findOneWhere([
            'url_key' => $slug,
            'locale'  => app()->getLocale(),
            'channel' => core()->getCurrentChannelCode(),
        ]);

        if (! $product) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->model), $slug
            );
        }

        return $product;
    }

    /**
     * Get product related to category.
     *
     * @param  int  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function getProductsRelatedToCategory($categoryId = null)
    {
        $qb = $this->model->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id');

        if ($categoryId) {
            $qb->where('product_categories.category_id', $categoryId);
        }

        return $qb->get();
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
     * Search product from database
     *
     * @param  string  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function searchFromDatabase($categoryId)
    {
        $params = request()->input();

        $query = $this->productFlatRepository->with([
            'images',
            'videos',
            'product.attribute_values',
            'product.price_indices',
            'product.inventory_indices',
            'product.reviews',
        ])->scopeQuery(function ($query) use ($params, $categoryId) {
            $prefix = DB::getTablePrefix();

            $qb = $query->distinct()
                ->select('product_flat.*')
                ->leftJoin('product_flat as variants', DB::raw('COALESCE(' . $prefix . 'variants.parent_id, ' . $prefix . 'variants.id)'), '=', 'product_flat.id')
                ->leftJoin('product_price_indices', function ($join) {
                    $customerGroup = $this->customerRepository->getCurrentGroup();

                    $join->on('product_flat.product_id', '=', 'product_price_indices.product_id')
                        ->where('product_price_indices.customer_group_id', $customerGroup->id);
                })
                ->where('product_flat.channel', core()->getRequestedChannelCode())
                ->where('product_flat.locale', core()->getRequestedLocaleCode())
                ->where('product_flat.status', 1)
                ->where('product_flat.visible_individually', 1)
                ->whereNotNull('product_flat.url_key');

            if ($categoryId) {
                $qb->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id')
                    ->whereIn('product_categories.category_id', explode(',', $categoryId));
            }

            if (isset($params['search'])) {
                $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');
            }

            if (isset($params['name'])) {
                $qb->where('product_flat.name', 'like', '%' . urldecode($params['name']) . '%');
            }

            if (isset($params['url_key'])) {
                $qb->where('product_flat.url_key', 'like', '%' . urldecode($params['url_key']) . '%');
            }

            #Filter collection by price
            if (! empty($params['price'])) {
                $priceRange = explode(',', $params['price']);

                $qb->whereBetween('product_price_indices.min_price', [
                    core()->convertToBasePrice(current($priceRange)),
                    core()->convertToBasePrice(end($priceRange)),
                ]);
            }

            $filterableAttributes = $this->attributeRepository->getProductDefaultAttributes(array_keys(
                request()->except([
                    'price',
                    'name',
                    'status',
                    'visible_individually',
                ])
            ));

            #Filter collection by attributes
            if ($filterableAttributes->isNotEmpty()) {
                $qb->leftJoin('product_attribute_values', 'variants.product_id', '=', 'product_attribute_values.product_id');

                $qb->where(function ($filterQuery) use ($filterableAttributes) {
                    foreach ($filterableAttributes as $attribute) {
                        $filterQuery->orWhere(function ($attributeQuery) use ($attribute) {
                            $attributeQuery = $attributeQuery->where('product_attribute_values.attribute_id', $attribute->id);

                            $filterAttributeValues = explode(',', request()->get($attribute->code));

                            if ($attribute->type == 'price') {
                                $attributeQuery->whereBetween('product_attribute_values.' . $attribute->column_name, [
                                    core()->convertToBasePrice(current($filterAttributeValues)),
                                    core()->convertToBasePrice(end($filterAttributeValues)),
                                ]);
                            } else {
                                $attributeQuery->whereIn('product_attribute_values.' . $attribute->column_name, $filterAttributeValues);
                            }
                        });
                    }
                });

                # this is key! if a product has been filtered down to the same number of attributes that we filtered on,
                # we know that it has matched all of the requested filters.
                $qb->groupBy('variants.id');
                $qb->havingRaw('COUNT(*) = ' . count($filterableAttributes));
            }

            #Sort collection
            $sortOptions = $this->getSortOptions($params);

            if ($sortOptions['order'] != 'rand') {
                $attribute = $this->attributeRepository->findOneByField('code', $sortOptions['sort']);
        
                if ($attribute) {
                    if ($attribute->code === 'price') {
                        $qb->orderBy('product_price_indices.min_price', $sortOptions['order']);
                    } else {
                        $qb->orderBy($attribute->code, $sortOptions['order']);
                    }
                } else {
                    /* `created_at` is not an attribute so it will be in else case */
                    $qb->orderBy('product_flat.created_at', $sortOptions['order']);
                }
            } else {
                return $qb->inRandomOrder();
            }
    
            return $qb->groupBy('product_flat.id');
        });

        # apply scope query so we can fetch the raw sql and perform a count
        $query->applyScope();

        $countQuery = clone $query->model;

        $count = collect(
            DB::select("select count(id) as aggregate from ({$countQuery->select('product_flat.id')->reorder('product_flat.id')->toSql()}) c",
            $countQuery->getBindings())
        )->pluck('aggregate')->first();

        $items = [];

        $limit = $this->getPerPageLimit($params);

        $currentPage = Paginator::resolveCurrentPage('page');

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $query->scopeQuery(function ($query) use ($currentPage, $limit) {
                return $query->forPage($currentPage, $limit);
            });

            $items = $query->get();
        }

        $results = new LengthAwarePaginator($items, $count, $limit, $currentPage, [
            'path'  => request()->url(),
            'query' => request()->query(),
        ]);

        return $results;
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
            'page'  => $currentPage,
            'limit' => $limit,
            'sort'  => $sortOptions['sort'],
            'order' => $sortOptions['order'],
        ]);

        $query = $this->productFlatRepository->with([
            'images',
            'videos',
            'product.attribute_values',
            'product.price_indices',
            'product.inventory_indices',
            'product.reviews',
        ])->scopeQuery(function ($query) use ($indices) {
            $qb = $query->distinct()
                ->whereIn('product_flat.product_id', $indices['ids'])
                ->where('product_flat.channel', core()->getRequestedChannelCode())
                ->where('product_flat.locale', core()->getRequestedLocaleCode());

            #Sort collection
            $qb->orderBy(DB::raw('FIELD(product_id, ' . implode(',', $indices['ids']) . ')'));

            return $qb;
        });

        $items = $indices['total'] ? $query->get() : [];

        $currentPage = Paginator::resolveCurrentPage('page');

        $limit = $this->getPerPageLimit($params);

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
     * @return integer
     */
    public function getPerPageLimit($params)
    {
        $limit = $params['limit'] ?? 9;

        if (core()->getConfigData('catalog.products.storefront.products_per_page')) {
            $pages = explode(',', core()->getConfigData('catalog.products.storefront.products_per_page'));

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
     * Search product by attribute.
     *
     * @param  string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchProductByAttribute($term)
    {
        $results = $this->productFlatRepository
            ->scopeQuery(function ($query) use ($term) {
                $query = $query->distinct()
                    ->addSelect('product_flat.*')
                    ->where('product_flat.channel', core()->getRequestedChannelCode())
                    ->where('product_flat.locale', core()->getRequestedLocaleCode())
                    ->whereNotNull('product_flat.url_key');

                return $query->where('product_flat.status', 1)
                    ->where('product_flat.visible_individually', 1)
                    ->where(function ($subQuery) use ($term) {
                        $queries = explode('_', $term);

                        foreach (array_map('trim', $queries) as $value) {
                            $subQuery->orWhere('product_flat.name', 'like', '%' . urldecode($value) . '%')
                                ->orWhere('product_flat.short_description', 'like', '%' . urldecode($value) . '%');
                        }
                    })
                    ->orderBy('product_id', 'desc');
            })->paginate(16);

        return $results;
    }

    /**
     * Search simple products for grouped product association.
     *
     * @param  string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchSimpleProducts($term)
    {
        return $this->productFlatRepository->scopeQuery(function ($query) use ($term) {
            $channel = core()->getRequestedChannelCode();

            $locale = core()->getRequestedLocaleCode();

            return $query->distinct()
                ->addSelect('product_flat.*')
                ->addSelect('product_flat.product_id as id')
                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                ->leftJoin('product_inventories', 'product_flat.product_id', '=', 'product_inventories.product_id')
                ->where('products.type', 'simple')
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->where('product_flat.status', '1')
                ->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                ->where('product_inventories.qty','>','0')
                ->orderBy('product_id', 'desc');
        })->get();
    }

    /**
     * Return category product maximum price.
     *
     * @param  integer  $categoryId
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
            ->max('max_price');
    }
}
