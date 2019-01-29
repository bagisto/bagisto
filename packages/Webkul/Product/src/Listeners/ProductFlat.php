<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Models\ProductAttributeValue;

/**
 * Product Flat Event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @author    Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductFlat
{
    /**
     * AttributeOptionRepository Repository Object
     *
     * @var object
     */
    protected $attributeOptionRepository;

    /**
     * ProductFlatRepository Repository Object
     *
     * @var object
     */
    protected $productFlatRepository;

    /**
     * ProductAttributeValueRepository Repository Object
     *
     * @var object
     */
    protected $productAttributeValueRepository;

    /**
     * Attribute Object
     *
     * @var object
     */
    protected $attribute;

    /**
     * @var object
     */
    public $attributeTypeFields = [
        'text' => 'text',
        'textarea' => 'text',
        'price' => 'float',
        'boolean' => 'boolean',
        'select' => 'integer',
        'multiselect' => 'text',
        'datetime' => 'datetime',
        'date' => 'date',
    ];

    /**
     * Create a new listener instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository     $attributeOptionRepository
     * @param  Webkul\Product\Repositories\ProductFlatRepository           $productFlatRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $productAttributeValueRepository
     * @return void
     */
    public function __construct(
        AttributeOptionRepository $attributeOptionRepository,
        ProductFlatRepository $productFlatRepository,
        ProductAttributeValueRepository $productAttributeValueRepository
    )
    {
        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->productAttributeValueRepository = $productAttributeValueRepository;

        $this->productFlatRepository = $productFlatRepository;
    }

    /**
     * After the attribute is created
     *
     * @return void
     */
    public function afterAttributeCreatedUpdated($attribute)
    {
        if(!$attribute->is_user_defined) {
            return false;
        }

        if (Schema::hasTable('product_flat')) {
            if (!Schema::hasColumn('product_flat', $attribute->code)) {
                Schema::table('product_flat', function (Blueprint $table) use($attribute) {
                    $table->{$this->attributeTypeFields[$attribute->type]}($attribute->code)->nullable();

                    if ($attribute->type == 'select' || $attribute->type == 'multiselect') {
                        $table->string($attribute->code . '_label')->nullable();
                    }
                });
            }
        }
    }

    public function afterAttributeDeleted($attribute)
    {
        if (Schema::hasTable('product_flat')) {
            if (Schema::hasColumn('product_flat', strtolower($attribute->code))) {
                Schema::table('product_flat', function (Blueprint $table) use($attribute) {
                    $table->dropColumn($attribute->code);

                    if ($attribute->type == 'select' || $attribute->type == 'multiselect') {
                        $table->dropColumn($attribute->code . '_label');
                    }
                });
            }
        }
    }

    /**
     * Creates product flat
     *
     * @param Product $product
     * @return void
     */
    public function afterProductCreatedUpdated($product)
    {
        $this->createFlat($product);

        if ($product->type == 'configurable') {
            foreach ($product->variants()->get() as $variant) {
                $this->createFlat($variant, $product);
            }
        }
    }

    /**
     * Creates product flat
     *
     * @param Product $product
     * @param Product $parentProduct
     * @return void
     */
    public function createFlat($product, $parentProduct = null)
    {
        foreach (core()->getAllChannels() as $channel) {
            foreach ($channel->locales as $locale) {
                $productFlat = $this->productFlatRepository->findOneWhere([
                    'product_id' => $product->id,
                    'channel' => $channel->code,
                    'locale' => $locale->code
                ]);

                if (!$productFlat) {
                    $productFlat = $this->productFlatRepository->create([
                        'product_id' => $product->id,
                        'channel' => $channel->code,
                        'locale' => $locale->code
                    ]);
                }

                foreach ($product->attribute_family->custom_attributes as $attribute) {
                    if (!Schema::hasTable('product_flat') || !Schema::hasColumn('product_flat', $attribute->code))
                        continue;

                    if ($attribute->value_per_channel) {
                        if ($attribute->value_per_locale) {
                            $productAttributeValue = $product->attribute_values()->where('channel', $channel->code)->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
                        } else {
                            $productAttributeValue = $product->attribute_values()->where('channel', $channel->code)->where('attribute_id', $attribute->id)->first();
                        }
                    } else {
                        if ($attribute->value_per_locale) {
                            $productAttributeValue = $product->attribute_values()->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
                        } else {
                            $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
                        }
                    }

                    if ($product->type == 'configurable' && $attribute->code == 'price') {
                        $productFlat->{$attribute->code} = app('Webkul\Product\Helpers\Price')->getVariantMinPrice($product);
                    } else {
                        $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]];
                    }

                    if ($attribute->type == 'select') {
                        $attributeOption = $this->attributeOptionRepository->find($product->{$attribute->code});

                        if ($attributeOption) {
                            if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                $productFlat->{$attribute->code . '_label'} = $attributeOptionTranslation->label;
                            } else {
                                $productFlat->{$attribute->code . '_label'} = $attributeOption->admin_name;
                            }
                        }
                    }
                }

                $productFlat->created_at = $product->created_at;

                $productFlat->updated_at = $product->updated_at;

                if ($parentProduct) {
                    $parentProductFlat = $this->productFlatRepository->findOneWhere([
                            'product_id' => $parentProduct->id,
                            'channel' => $channel->code,
                            'locale' => $locale->code
                        ]);

                    if ($parentProductFlat) {
                        $productFlat->parent_id = $parentProductFlat->id;
                    }
                }

                $productFlat->save();
            }
        }
    }
}