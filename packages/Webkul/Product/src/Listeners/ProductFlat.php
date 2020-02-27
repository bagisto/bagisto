<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Helpers\ProductType;
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
     * AttributeRepository Repository Object
     *
     * @var object
     */
    protected $attributeRepository;

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
        'text'        => 'text',
        'textarea'    => 'text',
        'price'       => 'float',
        'boolean'     => 'boolean',
        'select'      => 'integer',
        'multiselect' => 'text',
        'datetime'    => 'datetime',
        'date'        => 'date',
        'file'        => 'text',
        'image'       => 'text',
        'checkbox'    => 'text'
    ];

    /**
     * Create a new listener instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository           $attributeRepository
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository     $attributeOptionRepository
     * @param  Webkul\Product\Repositories\ProductFlatRepository           $productFlatRepository
     * @param  Webkul\Product\Repositories\ProductAttributeValueRepository $productAttributeValueRepository
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        AttributeOptionRepository $attributeOptionRepository,
        ProductFlatRepository $productFlatRepository,
        ProductAttributeValueRepository $productAttributeValueRepository
    )
    {
        $this->attributeRepository = $attributeRepository;

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
        if (! $attribute->is_user_defined) {
            return false;
        }

        if (! $attribute->use_in_flat) {
            $this->afterAttributeDeleted($attribute->id);
            return false;
        }

        if (! Schema::hasColumn('product_flat', $attribute->code)) {
            Schema::table('product_flat', function (Blueprint $table) use($attribute) {
                $table->{$this->attributeTypeFields[$attribute->type]}($attribute->code)->nullable();

                if ($attribute->type == 'select' || $attribute->type == 'multiselect') {
                    $table->string($attribute->code . '_label')->nullable();
                }
            });
        }
    }

    public function afterAttributeDeleted($attributeId)
    {
        $attribute = $this->attributeRepository->find($attributeId);

        if (Schema::hasColumn('product_flat', strtolower($attribute->code))) {
            Schema::table('product_flat', function (Blueprint $table) use($attribute) {
                $table->dropColumn($attribute->code);

                if ($attribute->type == 'select' || $attribute->type == 'multiselect') {
                    $table->dropColumn($attribute->code . '_label');
                }
            });
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

        if (ProductType::hasVariants($product->type)) {
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
        static $familyAttributes = [];

        static $superAttributes = [];

        if (! array_key_exists($product->attribute_family->id, $familyAttributes)) {
            $familyAttributes[$product->attribute_family->id] = $product->attribute_family->custom_attributes;
        }

        if ($parentProduct && ! array_key_exists($parentProduct->id, $superAttributes)) {
            $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();
        }

        if (isset($product['channels'])) {
            foreach ($product['channels'] as $channel) {
                $channel = app('Webkul\Core\Repositories\ChannelRepository')->findOrFail($channel);
                $channels[] = $channel['code'];
            }
        } elseif (isset($parentProduct['channels'])){
            foreach ($parentProduct['channels'] as $channel) {
                $channel = app('Webkul\Core\Repositories\ChannelRepository')->findOrFail($channel);
                $channels[] = $channel['code'];
            }
        } else {
            $channels[] = core()->getDefaultChannelCode();
        }

        foreach (core()->getAllChannels() as $channel) {
            if (in_array($channel->code, $channels)) {
                foreach ($channel->locales as $locale) {
                    $productFlat = $this->productFlatRepository->findOneWhere([
                        'product_id' => $product->id,
                        'channel'    => $channel->code,
                        'locale'     => $locale->code
                    ]);

                    if (! $productFlat) {
                        $productFlat = $this->productFlatRepository->create([
                            'product_id' => $product->id,
                            'channel'    => $channel->code,
                            'locale'     => $locale->code
                        ]);
                    }

                    foreach ($familyAttributes[$product->attribute_family->id] as $attribute) {
                        if ($parentProduct && ! in_array($attribute->code, array_merge($superAttributes[$parentProduct->id], ['sku', 'name', 'price', 'weight', 'status']))) {
                            continue;
                        }

                        if (in_array($attribute->code, ['tax_category_id'])) {
                            continue;
                        }

                        if (! Schema::hasColumn('product_flat', $attribute->code)) {
                            continue;
                        }

                        if ($attribute->value_per_channel) {
                            if ($attribute->value_per_locale) {
                                $productAttributeValue = $product->attribute_values()
                                                                 ->where('channel', $channel->code)
                                                                 ->where('locale', $locale->code)
                                                                 ->where('attribute_id', $attribute->id)
                                                                 ->first();
                            } else {
                                $productAttributeValue = $product->attribute_values()
                                                                 ->where('channel', $channel->code)
                                                                 ->where('attribute_id', $attribute->id)
                                                                 ->first();
                            }
                        } else {
                            if ($attribute->value_per_locale) {
                                $productAttributeValue = $product->attribute_values()->where('locale', $locale->code)->where('attribute_id', $attribute->id)->first();
                            } else {
                                $productAttributeValue = $product->attribute_values()->where('attribute_id', $attribute->id)->first();
                            }
                        }

                        $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]] ?? null;

                        if ($attribute->type == 'select') {
                            $attributeOption = $this->attributeOptionRepository->find($product->{$attribute->code});

                            if ($attributeOption) {
                                if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                    $productFlat->{$attribute->code . '_label'} = $attributeOptionTranslation->label;
                                } else {
                                    $productFlat->{$attribute->code . '_label'} = $attributeOption->admin_name;
                                }
                            }
                        } elseif ($attribute->type == 'multiselect') {
                            $attributeOptionIds = explode(',', $product->{$attribute->code});

                            if (count($attributeOptionIds)) {
                                $attributeOptions = $this->attributeOptionRepository->findWhereIn('id', $attributeOptionIds);

                                $optionLabels = [];

                                foreach ($attributeOptions as $attributeOption) {
                                    if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                        $optionLabels[] = $attributeOptionTranslation->label;
                                    } else {
                                        $optionLabels[] = $attributeOption->admin_name;
                                    }
                                }

                                $productFlat->{$attribute->code . '_label'} = implode(', ', $optionLabels);
                            }
                        }
                    }

                    $productFlat->created_at = $product->created_at;

                    $productFlat->updated_at = $product->updated_at;

                    $productFlat->min_price = $product->getTypeInstance()->getMinimalPrice();

                    $productFlat->max_price = $product->getTypeInstance()->getMaximamPrice();

                    if ($parentProduct) {
                        $parentProductFlat = $this->productFlatRepository->findOneWhere([
                                'product_id' => $parentProduct->id,
                                'channel'    => $channel->code,
                                'locale'     => $locale->code
                            ]);

                        if ($parentProductFlat) {
                            $productFlat->parent_id = $parentProductFlat->id;
                        }
                    }

                    $productFlat->save();
                }
            } else {
                $route = request()->route() ? request()->route()->getName() : "";

                if ($route == 'admin.catalog.products.update') {
                    $productFlat = $this->productFlatRepository->findOneWhere([
                        'product_id' => $product->id,
                        'channel'    => $channel->code,
                    ]);

                    if ($productFlat) {
                        $this->productFlatRepository->delete($productFlat->id);
                    }
                }
            }
        }
    }
}