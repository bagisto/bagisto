<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductImageRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Contracts\Criteria\SortCriteria;
use Webkul\Product\Contracts\Criteria\ActiveProductCriteria;
use Webkul\Product\Contracts\Criteria\AttributeToSelectCriteria;
use Webkul\Product\Contracts\Criteria\FilterByAttributesCriteria;
use Webkul\Product\Contracts\Criteria\FilterByCategoryCriteria;
use Webkul\Product\Contracts\Criteria\NewProductsCriteria;
use Webkul\Product\Contracts\Criteria\FeaturedProductsCriteria;
use Webkul\Product\Contracts\Criteria\SearchByAttributeCriteria;
use Webkul\Product\Contracts\Criteria\SearchByCategoryCriteria;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Product Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var array
     */
    protected $attribute;

    /**
     * AttributeOptionRepository object
     *
     * @var array
     */
    protected $attributeOption;

    /**
     * ProductAttributeValueRepository object
     *
     * @var array
     */
    protected $attributeValue;

    /**
     * ProductInventoryRepository object
     *
     * @var array
     */
    protected $productInventory;

    /**
     * ProductImageRepository object
     *
     * @var array
     */
    protected $productImage;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository           $attribute
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository     $attributeOption
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $attributeValue
     * @param  Webkul\Product\Repositories\ProductInventoryRepository      $productInventory
     * @param  Webkul\Product\Repositories\ProductImageRepository          $productImage
     * @return void
     */
    public function __construct(
        AttributeRepository $attribute,
        AttributeOptionRepository $attributeOption,
        ProductAttributeValueRepository $attributeValue,
        ProductInventoryRepository $productInventory,
        ProductImageRepository $productImage,
        App $app)
    {
        $this->attribute = $attribute;

        $this->attributeOption = $attributeOption;

        $this->attributeValue = $attributeValue;

        $this->productInventory = $productInventory;

        $this->productImage = $productImage;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Models\Product';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $product = $this->model->create($data);

        $nameAttribute = $this->attribute->findOneByField('code', 'status');
        $this->attributeValue->create([
                'product_id' => $product->id,
                'attribute_id' => $nameAttribute->id,
                'value' => 1
            ]);

        if(isset($data['super_attributes'])) {

            $super_attributes = [];

            foreach ($data['super_attributes'] as $attributeCode => $attributeOptions) {
                $attribute = $this->attribute->findOneByField('code', $attributeCode);

                $super_attributes[$attribute->id] = $attributeOptions;

                $product->super_attributes()->attach($attribute->id);
            }

            foreach (array_permutation($super_attributes) as $permutation) {
                $this->createVariant($product, $permutation);
            }
        }

        return $product;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $product = $this->find($id);

        if($product->parent_id && $this->checkVariantOptionAvailabiliy($data, $product)) {
            $data['parent_id'] = NULL;
        }

        $product->update($data);

        if(isset($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        $attributes = $product->attribute_family->custom_attributes;

        foreach ($attributes as $attribute) {
            if(!isset($data[$attribute->code]) || (in_array($attribute->type, ['date', 'datetime']) && !$data[$attribute->code]))
                continue;

            $attributeValue = $this->attributeValue->findOneWhere([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null
                ]);

            if(!$attributeValue) {
                $this->attributeValue->create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'value' => $data[$attribute->code],
                    'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null
                ]);
            } else {
                $this->attributeValue->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                    ], $attributeValue->id
                );
            }
        }

        $previousVariantIds = $product->variants->pluck('id');

        if(isset($data['variants'])) {
            foreach ($data['variants'] as $variantId => $variantData) {
                if (str_contains($variantId, 'variant_')) {
                    $permutation = [];
                    foreach ($product->super_attributes as $superAttribute) {
                        $permutation[$superAttribute->id] = $variantData[$superAttribute->code];
                    }

                    $this->createVariant($product, $permutation, $variantData);
                } else {
                    if(is_numeric($index = $previousVariantIds->search($variantId))) {
                        $previousVariantIds->forget($index);
                    }

                    $variantData['channel'] = $data['channel'];
                    $variantData['locale'] = $data['locale'];

                    $this->updateVariant($variantData, $variantId);
                }
            }
        }

        foreach ($previousVariantIds as $variantId) {
            $this->delete($variantId);
        }

        $this->productInventory->saveInventories($data, $product);

        $this->productImage->uploadImages($data, $product);

        return $product;
    }

    /**
     * @param mixed $product
     * @param array $permutation
     * @param array $data
     * @return mixed
     */
    public function createVariant($product, $permutation, $data = [])
    {
        if(!count($data)) {
            $data = [
                    "sku" => $product->sku . '-variant-' . implode('-', $permutation),
                    "name" => "",
                    "inventories" => [],
                    "price" => 0,
                    "weight" => 0,
                    "status" => 1
                ];
        }

        $variant = $this->model->create([
                'parent_id' => $product->id,
                'type' => 'simple',
                'attribute_family_id' => $product->attribute_family_id,
                'sku' => $data['sku'],
            ]);

        foreach (['sku', 'name', 'price', 'weight', 'status'] as $attributeCode) {
            $attribute = $this->attribute->findOneByField('code', $attributeCode);

            if($attribute->value_per_channel) {
                if($attribute->value_per_locale) {
                    foreach(core()->getAllChannels() as $channel) {
                        foreach(core()->getAllLocales() as $locale) {
                            $this->attributeValue->create([
                                    'product_id' => $variant->id,
                                    'attribute_id' => $attribute->id,
                                    'channel' => $channel->code,
                                    'locale' => $locale->code,
                                    'value' => $data[$attributeCode]
                                ]);
                        }
                    }
                } else {
                    foreach(core()->getAllChannels() as $channel) {
                        $this->attributeValue->create([
                                'product_id' => $variant->id,
                                'attribute_id' => $attribute->id,
                                'channel' => $channel->code,
                                'value' => $data[$attributeCode]
                            ]);
                    }
                }
            } else {
                if($attribute->value_per_locale) {
                    foreach(core()->getAllLocales() as $locale) {
                        $this->attributeValue->create([
                                'product_id' => $variant->id,
                                'attribute_id' => $attribute->id,
                                'locale' => $locale->code,
                                'value' => $data[$attributeCode]
                            ]);
                    }
                } else {
                    $this->attributeValue->create([
                            'product_id' => $variant->id,
                            'attribute_id' => $attribute->id,
                            'value' => $data[$attributeCode]
                        ]);
                }
            }
        }

        foreach($permutation as $attributeId => $optionId) {
            $this->attributeValue->create([
                    'product_id' => $variant->id,
                    'attribute_id' => $attributeId,
                    'value' => $optionId
                ]);
        }

        $this->productInventory->saveInventories($data, $variant);

        return $variant;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function updateVariant(array $data, $id)
    {
        $variant = $this->find($id);

        $variant->update(['sku' => $data['sku']]);

        foreach (['sku', 'name', 'price', 'weight', 'status'] as $attributeCode) {
            $attribute = $this->attribute->findOneByField('code', $attributeCode);

            $attributeValue = $this->attributeValue->findOneWhere([
                    'product_id' => $id,
                    'attribute_id' => $attribute->id,
                    'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null
                ]);

            if(!$attributeValue) {
                $this->attributeValue->create([
                        'product_id' => $id,
                        'attribute_id' => $attribute->id,
                        'value' => $data[$attribute->code],
                        'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                        'locale' => $attribute->value_per_locale ? $data['locale'] : null
                    ]);
            } else {
                $this->attributeValue->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $data[$attribute->code]
                ], $attributeValue->id);
            }
        }

        $this->productInventory->saveInventories($data, $variant);

        return $variant;
    }

    /**
     * Change an attribute's value of the product
     *
     * @return boolean
     */
    public function updateAttribute($product, $attribute, $value) {
        $attribute = $this->attribute->findOneByField('code', 'status');

        $attributeValue = $this->attributeValue->findOneWhere([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
        ]);

        $result = $this->attributeValue->update([
            ProductAttributeValue::$attributeTypeFields[$attribute->type] => $value
        ], $attributeValue->id);

        if($result)
            return true;
        else
            return false;
    }

    /**
     * @param array $data
     * @param mixed $product
     * @return mixed
     */
    public function checkVariantOptionAvailabiliy($data, $product)
    {
        $parent = $product->parent;

        $superAttributeCodes = $parent->super_attributes->pluck('code');

        $isAlreadyExist = false;

        foreach ($parent->variants as $variant) {
            if($variant->id == $product->id)
                continue;

            $matchCount = 0;

            foreach ($superAttributeCodes as $attributeCode) {
                if($data[$attributeCode] == $variant->{$attributeCode})
                    $matchCount++;
            }

            if($matchCount == $superAttributeCodes->count()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param integer $categoryId
     * @return Collection
     */
    public function findAllByCategory($categoryId = null)
    {
        $this->pushCriteria(app(ActiveProductCriteria::class));
        $this->pushCriteria(app(SortCriteria::class));
        $this->pushCriteria(app(FilterByAttributesCriteria::class));
        $this->pushCriteria(new FilterByCategoryCriteria($categoryId));
        $this->pushCriteria(app(AttributeToSelectCriteria::class)->addAttribueToSelect([
                'name',
                'description',
                'short_description',
                'price',
                'special_price',
                'special_price_from',
                'special_price_to'
            ]));

        $params = request()->input();

        return $this->scopeQuery(function($query){
                return $query->distinct()->addSelect('products.*');
            })->paginate(isset($params['limit']) ? $params['limit'] : 9, ['products.id']);
    }

    /**
     * Retrive product from slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findBySlugOrFail($slug, $columns = null)
    {
        $attribute = $this->attribute->findOneByField('code', 'url_key');

        $attributeValue = $this->attributeValue->findOneWhere([
            'attribute_id' => $attribute->id,
            ProductAttributeValue::$attributeTypeFields[$attribute->type] => $slug
        ], ['product_id']);

        if($attributeValue && $attributeValue->product_id) {
            $this->pushCriteria(app(ActiveProductCriteria::class));
            $this->pushCriteria(app(AttributeToSelectCriteria::class)->addAttribueToSelect($columns));

            $product = $this->findOrFail($attributeValue->product_id);

            return $product;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $slug
        );
    }

    /**
     * Returns newly added product
     *
     * @return Collection
     */
    public function getNewProducts()
    {
        $this->pushCriteria(app(ActiveProductCriteria::class));
        $this->pushCriteria(app(NewProductsCriteria::class));
        $this->pushCriteria(app(AttributeToSelectCriteria::class));

        $params = request()->input();

        return $this->scopeQuery(function($query) {
                return $query->distinct()->addSelect('products.*')->orderBy('id', 'desc');
            })->paginate(4, ['products.id']);
    }

    /**
     * Returns featured product
     *
     * @return Collection
     */
    public function getFeaturedProducts()
    {
        $this->pushCriteria(app(ActiveProductCriteria::class));
        $this->pushCriteria(app(FeaturedProductsCriteria::class));
        $this->pushCriteria(app(AttributeToSelectCriteria::class));

        $params = request()->input();

        return $this->scopeQuery(function($query) {
                return $query->distinct()->addSelect('products.*')->orderBy('id', 'desc');
            })->paginate(4, ['products.id']);
    }

    /**
     * Search Product by Attribute
     *
     * @return Collection
     */
    public function searchProductByAttribute($term) {
        $this->pushCriteria(app(ActiveProductCriteria::class));
        $this->pushCriteria(app(SearchByAttributeCriteria::class));

        return $this->scopeQuery(function($query) use($term) {
            return $query->distinct()->addSelect('products.*')->where('pav.text_value', 'like', '%'.$term.'%');
        })->paginate(4);
    }

    /**
     * break the search term into explode by using space and tell which exploded item is attribute
     * , category, super attribute or combination of them.
     */
    public function breakTheTerm($term) {
        $explodedTerm = (explode(" ", $term));

        dd($term);
    }
}