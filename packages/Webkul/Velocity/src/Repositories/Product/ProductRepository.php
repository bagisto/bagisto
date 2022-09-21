<?php

namespace Webkul\Velocity\Repositories\Product;

use Illuminate\Container\Container;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Models\ProductAttributeValue;

class ProductRepository extends BaseProductRepository
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        Container $container
    )
    {
        parent::__construct(
            $attributeRepository,
            $container
        );
    }

    /**
     * Search Product by Attribute
     *
     * @param  array  $params
     * @return \Illuminate\Support\Collection
     */
    public function searchProductsFromCategory($params)
    {
        $results = app(ProductFlatRepository::class)->scopeQuery(function($query) use($params) {
            $channel = core()->getRequestedChannelCode();

            $locale = core()->getRequestedLocaleCode();

            if (! core()->getConfigData('catalog.products.homepage.out_of_stock_items')) {
                $query = app('Webkul\Product\Repositories\ProductRepository')->checkOutOfStockItem($query);
            }

            $query = $query->distinct()
                ->addSelect('product_flat.*')
                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->where('product_flat.status', 1)
                ->where('product_flat.visible_individually', 1)
                ->where('product_flat.channel', $channel)
                ->where('product_flat.locale', $locale)
                ->whereNotNull('product_flat.url_key');

            if (! empty($params['term'])) {
                $query->where('product_flat.name', 'like', '%' . urldecode($params['term']) . '%');
            }

            if (! empty($params['category'])) {
                $query = $query->where('product_categories.category_id', $params['category']);
            }

            if (isset($params['sort'])) {
                $attribute = $this->attributeRepository->findOneByField('code', $params['sort']);

                if ($params['sort'] == 'price') {
                    if ($attribute->code == 'price') {
                        $query->orderBy('min_price', $params['order']);
                    } else {
                        $query->orderBy($attribute->code, $params['order']);
                    }
                } else {
                    $query->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
                }
            }

            $query = $query->leftJoin('products as variants', 'products.id', '=', 'variants.parent_id');

            $query = $query->where(function($query1) use($query) {
                $aliases = [
                    'products' => 'filter_',
                    'variants' => 'variant_filter_',
                ];

                foreach($aliases as $table => $alias) {
                    $query1 = $query1->orWhere(function($query2) use ($query, $table, $alias) {

                        foreach ($this->attributeRepository->getProductDefaultAttributes(array_keys(request()->input())) as $code => $attribute) {
                            $aliasTemp = $alias . $attribute->code;

                            $query = $query->leftJoin('product_attribute_values as ' . $aliasTemp, $table . '.id', '=', $aliasTemp . '.product_id');

                            $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

                            $temp = explode(',', request()->get($attribute->code));

                            if ($attribute->type != 'price') {
                                $query2 = $query2->where($aliasTemp . '.attribute_id', $attribute->id);

                                $query2 = $query2->where(function($query3) use($aliasTemp, $column, $temp) {
                                    foreach($temp as $code => $filterValue) {
                                        if (! is_numeric($filterValue))
                                            continue;

                                        $columns = $aliasTemp . '.' . $column;
                                        $query3 = $query3->orwhereRaw("find_in_set($filterValue, $columns)");
                                    }
                                });
                            } else {
                                $query2->where('product_flat.min_price', '>=', core()->convertToBasePrice(current($temp)))
                                    ->where('product_flat.min_price', '<=', core()->convertToBasePrice(end($temp)));
                            }
                        }
                    });
                }
            });

            return $query->groupBy('product_flat.id');
        })->paginate(isset($params['limit']) ? $params['limit'] : 9);

        return $results;
    }
}