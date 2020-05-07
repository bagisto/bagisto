<?php

namespace Webkul\Bulkupload\Repositories;

use Illuminate\Container\Container as App;
use DB;
use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use  Webkul\Product\Repositories\ProductImageRepository;
use Storage;

/**
 * BulkProduct Repository
 *
 * @author    Prateek Sivastava
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class BulkProductRepository extends Repository
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
     * ProductRepository object
     *
     * @var array
     */
    protected $productRepository;

    /**
     * ProductAttributeValueRepository object
     *
     * @var array
     */
    protected $attributeValue;

    /**
     * ProductFlatRepository object
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


    public function __construct(
        AttributeRepository $attribute,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValue,
         ProductInventoryRepository $productInventory,
        ProductImageRepository $productImage,
        App $app)
    {
        $this->attribute = $attribute;

        // $this->attributeOption = $attributeOption;

        $this->attributeValue = $attributeValue;

        $this->productRepository = $productRepository;

        $this->productInventory = $productInventory;

        $this->productImage = $productImage;

        parent::__construct($app);
    }

    /**->where('product_flat.visible_individually', 1)
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Product\Contracts\Product';
    }

    public function productRepositoryUpdateForVariants(array $data, $id, $attribute = "id")
    {
        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->find($id);

        $configurable = app('Webkul\Product\Type\Configurable');

        if ($product->parent_id && $configurable->checkVariantOptionAvailabiliy($data, $product)) {
            $data['parent_id'] = NULL;
        }

        $product->update($data);

        $attributes = $product->attribute_family->custom_attributes;

        foreach ($attributes as $attribute) {
            if (! isset($data[$attribute->code]) || (in_array($attribute->type, ['date', 'datetime']) && ! $data[$attribute->code]))
                continue;

            if ($attribute->type == 'multiselect' || $attribute->type == 'checkbox') {
                $data[$attribute->code] = implode(",", $data[$attribute->code]);
            }

            if ($attribute->type == 'image' || $attribute->type == 'file') {
                $dir = 'product';
                if (gettype($data[$attribute->code]) == 'object') {
                    $data[$attribute->code] = request()->file($attribute->code)->store($dir);
                } else {
                    $data[$attribute->code] = NULL;
                }
            }

            $attributeValue = $this->attributeValue->findOneWhere([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'channel' => $attribute->value_per_channel ? $data['channel'] : null,
                    'locale' => $attribute->value_per_locale ? $data['locale'] : null
                ]);

            if (! $attributeValue) {
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

                if ($attribute->type == 'image' || $attribute->type == 'file') {
                    Storage::delete($attributeValue->text_value);
                }
            }
        }

        if (request()->route()->getName() != 'admin.catalog.products.massupdate') {
            if  (isset($data['categories'])) {
                $product->categories()->sync($data['categories']);
            }

            if (isset($data['up_sell'])) {
                $product->up_sells()->sync($data['up_sell']);
            } else {
                $data['up_sell'] = [];
                $product->up_sells()->sync($data['up_sell']);
            }

            if (isset($data['cross_sell'])) {
                $product->cross_sells()->sync($data['cross_sell']);
            } else {
                $data['cross_sell'] = [];
                $product->cross_sells()->sync($data['cross_sell']);
            }

            if (isset($data['related_products'])) {
                $product->related_products()->sync($data['related_products']);
            } else {
                $data['related_products'] = [];
                $product->related_products()->sync($data['related_products']);
            }

            $previousVariantIds = $product->variants->pluck('id');
            if (isset($data['variants'])) {
                foreach ($data['variants'] as $variantId => $variantData) {
                    if (str_contains($variantId, 'variant_')) {
                        $permutation = [];
                        foreach ($product->super_attributes as $superAttribute) {
                            $permutation[$superAttribute->id] = $variantData[$superAttribute->code];
                        }

                        $this->productRepository>createVariant($product, $permutation, $variantData);
                    } else {

                        if (is_numeric($index = $previousVariantIds->search($variantId))) {
                            $previousVariantIds->forget($index);
                        }

                        $variantData['channel'] = $data['channel'];
                        $variantData['locale'] = $data['locale'];

                        $this->productRepository->updateVariant($variantData, $variantId);
                    }
                }
            }

            // foreach ($previousVariantIds as $variantId) {
            //     $this->delete($variantId);
            // }

            $this->productInventory->saveInventories($data, $product);

            $this->productImage->uploadImages($data, $product);
        }

        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }

        Event::dispatch('catalog.product.update.after', $product);

        return $product;
    }

}