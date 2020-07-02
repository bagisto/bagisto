<?php

namespace Webkul\Product\Repositories;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Models\ProductAttributeValueProxy;

class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new repository instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        App $app
    )
    {
        $this->attributeRepository = $attributeRepository;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        Event::dispatch('catalog.product.create.before');

        $typeInstance = app(config('product_types.' . $data['type'] . '.class'));

        $product = $typeInstance->create($data);

        Event::dispatch('catalog.product.create.after', $product);

        return $product;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = "id")
    {
        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->find($id);

        $product = $product->getTypeInstance()->update($data, $id, $attribute);

        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }

        Event::dispatch('catalog.product.update.after', $product);

        return $product;
    }

    /**
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        Event::dispatch('catalog.product.delete.before', $id);

        parent::delete($id);

        Event::dispatch('catalog.product.delete.after', $id);
    }

    /**
     * @param  int  $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function getAll($categoryId = null)
    {
        $params = request()->input();

        if (core()->getConfigData('catalog.products.storefront.products_per_page')) {
            $pages = explode(',', core()->getConfigData('catalog.products.storefront.products_per_page'));

            $perPage = isset($params['limit']) ? $params['limit'] : current($pages);
        } else {
            $perPage = isset($params['limit']) ? $params['limit'] : 9;
        }

        $page = Paginator::resolveCurrentPage('page');

        $repository = app(ProductFlatRepository::class)->scopeQuery(function($query) use($params, $categoryId) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                ->select('product_flat.*')
                ->join('product_flat as variants', 'product_flat.id', '=', DB::raw('COALESCE(variants.parent_id, variants.id)'))
                ->leftJoin('product_categories', 'product_categories.product_id', '=', 'product_flat.product_id')
                ->leftJoin('product_attribute_values', 'product_attribute_values.product_id', '=', 'variants.product_id')
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->whereNotNull('product_flat.url_key');

            if ($categoryId) {
                $qb->where('product_categories.category_id', $categoryId);
            }

            if (is_null(request()->input('status'))) {
                $qb->where('product_flat.status', 1);
            }

            if (is_null(request()->input('visible_individually'))) {
                $qb->where('product_flat.visible_individually', 1);
            }

            if (isset($params['search']))
                $qb->where('product_flat.name', 'like', '%' . urldecode($params['search']) . '%');

            # sort direction
            $orderDirection = 'asc';
            if( isset($params['order']) && in_array($params['order'], ['desc', 'asc']) ){
                $orderDirection = $params['order'];
            }

            if (isset($params['sort'])) {
                $attribute = $this->attributeRepository->findOneByField('code', $params['sort']);

                if ($attribute) {
                    if ($attribute->code == 'price') {
                        $qb->orderBy('min_price', $orderDirection);
                    } else {
                        $qb->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $orderDirection);
                    }
                }
            }

            if ( $priceFilter = request('price') ){
                $priceRange = explode(',', $priceFilter);
                if( count($priceRange) > 0 ) {
                    $qb->where('variants.min_price', '>=', core()->convertToBasePrice($priceRange[0]));
                    $qb->where('variants.min_price', '<=', core()->convertToBasePrice(end($priceRange)));
                }
            }

            $attributeFilters = $this->attributeRepository
                ->getProductDefaultAttributes(array_keys(
                    request()->except(['price'])
                ));

            if ( count($attributeFilters) > 0 ) {
                $qb->where(function ($filterQuery) use($attributeFilters){

                    foreach ($attributeFilters as $attribute) {
                        $filterQuery->orWhere(function ($attributeQuery) use ($attribute) {

                            $column = 'product_attribute_values.' . ProductAttributeValueProxy::modelClass()::$attributeTypeFields[$attribute->type];

                            $filterInputValues = explode(',', request()->get($attribute->code));

                            # define the attribute we are filtering
                            $attributeQuery = $attributeQuery->where('product_attribute_values.attribute_id', $attribute->id);

                            # apply the filter values to the correct column for this type of attribute.
                            if ($attribute->type != 'price') {

                                $attributeQuery->where(function ($attributeValueQuery) use ($column, $filterInputValues) {
                                    foreach ($filterInputValues as $filterValue) {
                                        if (!is_numeric($filterValue)) {
                                            continue;
                                        }
                                        $attributeValueQuery->orWhereRaw("find_in_set(?, {$column})", [$filterValue]);
                                    }
                                });

                            } else {
                                $attributeQuery->where($column, '>=', core()->convertToBasePrice(current($filterInputValues)))
                                    ->where($column, '<=', core()->convertToBasePrice(end($filterInputValues)));
                            }
                        });
                    }

                });

                # this is key! if a product has been filtered down to the same number of attributes that we filtered on,
                # we know that it has matched all of the requested filters.
                $qb->groupBy('variants.id');
                $qb->havingRaw('COUNT(*) = ' . count($attributeFilters));
            }

            return $qb->groupBy('product_flat.id');

        });

        # apply scope query so we can fetch the raw sql and perform a count
        $repository->applyScope();
        $countQuery = "select count(*) as aggregate from ({$repository->model->toSql()}) c";
        $count = collect(DB::select($countQuery, $repository->model->getBindings()))->pluck('aggregate')->first();

        if ($count > 0) {
            # apply a new scope query to limit results to one page
            $repository->scopeQuery(function ($query) use ($page, $perPage) {
                return $query->forPage($page, $perPage);
            });

            # manually build the paginator
            $items = $repository->get();
        } else {
            $items = [];
        }

        $results = new \Illuminate\Pagination\LengthAwarePaginator($items, $count, $perPage, $page, [
            'path'  => request()->url(),
            'query' => request()->query()
        ]);

        return $results;
    }

    /**
     * Retrive product from slug
     *
     * @param  string  $slug
     * @param  string  $columns
     * @return \Webkul\Product\Contracts\Product
     */
    public function findBySlugOrFail($slug, $columns = null)
    {
        $product = app(ProductFlatRepository::class)->findOneWhere([
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
     * Retrieve product from slug without throwing an exception (might return null)
     *
     * @param  string  $slug
     * @return \Webkul\Product\Contracts\ProductFlat
     */
    public function findBySlug($slug)
    {
        return app(ProductFlatRepository::class)->findOneWhere([
            'url_key' => $slug,
            'locale'  => app()->getLocale(),
            'channel' => core()->getCurrentChannelCode(),
        ]);
    }

    /**
     * Returns newly added product
     *
     * @return \Illuminate\Support\Collection
     */
    public function getNewProducts()
    {
        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('product_flat.new', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->inRandomOrder();
        })->paginate(4);

        return $results;
    }

    /**
     * Returns featured product
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFeaturedProducts()
    {
        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('product_flat.featured', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->inRandomOrder();
        })->paginate(4);

        return $results;
    }

    /**
     * Search Product by Attribute
     *
     * @param  string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchProductByAttribute($term)
    {
        $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

        $locale = request()->get('locale') ?: app()->getLocale();

        if (config('scout.driver') == 'algolia') {
            $results = app(ProductFlatRepository::class)->getModel()::search('query', function ($searchDriver, string $query, array $options) use($term, $channel, $locale) {
                $queries = explode('_', $term);

                $options['similarQuery'] = array_map('trim', $queries);

                $searchDriver->setSettings([
                    'attributesForFaceting' => [
                    "searchable(locale)",
                    "searchable(channel)"
                    ]
                ]);

                $options['facetFilters'] = ['locale:' . $locale, 'channel:' .  $channel];

                return $searchDriver->search($query, $options);
            })
            ->where('status', 1)
            ->where('visible_individually', 1)
            ->orderBy('product_id', 'desc')
            ->paginate(16);
        } else if(config('scout.driver') == 'elastic') {
            $queries = explode('_', $term);

            $results = app(ProductFlatRepository::class)->getModel()::search(implode(' OR ', $queries))
                ->where('status', 1)
                ->where('visible_individually', 1)
                ->where('channel', $channel)
                ->where('locale', $locale)
                ->orderBy('product_id', 'desc')
                ->paginate(16);
        } else {
            $results = app(ProductFlatRepository::class)->scopeQuery(function($query) use($term, $channel, $locale) {
                return $query->distinct()
                            ->addSelect('product_flat.*')
                            ->where('product_flat.status', 1)
                            ->where('product_flat.visible_individually', 1)
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->whereNotNull('product_flat.url_key')
                            ->where(function($subQuery) use ($term) {  
                                $queries = explode('_', $term);

                                foreach (array_map('trim', $queries) as $value) {
                                    $subQuery->orWhere('product_flat.name', 'like', '%' . urldecode($value) . '%')
                                                ->orWhere('product_flat.short_description', 'like', '%' . urldecode($value) . '%');
                                }
                            })
                            ->orderBy('product_id', 'desc');
            })->paginate(16);
        }
        
        return $results;
    }

    /**
     * Returns product's super attribute with options
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getSuperAttributes($product)
    {
        $superAttrbutes = [];

        foreach ($product->super_attributes as $key => $attribute) {
            $superAttrbutes[$key] = $attribute->toArray();

            foreach ($attribute->options as $option) {
                $superAttrbutes[$key]['options'][] = [
                    'id'           => $option->id,
                    'admin_name'   => $option->admin_name,
                    'sort_order'   => $option->sort_order,
                    'swatch_value' => $option->swatch_value,
                ];
            }
        }

        return $superAttrbutes;
    }

    /**
     * Search simple products for grouped product association
     *
     * @param  string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchSimpleProducts($term)
    {
        return app(ProductFlatRepository::class)->scopeQuery(function($query) use($term) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            return $query->distinct()
                         ->addSelect('product_flat.*')
                         ->addSelect('product_flat.product_id as id')
                         ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                         ->where('products.type', 'simple')
                         ->where('product_flat.channel', $channel)
                         ->where('product_flat.locale', $locale)
                         ->where('product_flat.name', 'like', '%' . urldecode($term) . '%')
                         ->orderBy('product_id', 'desc');
        })->get();
    }
}