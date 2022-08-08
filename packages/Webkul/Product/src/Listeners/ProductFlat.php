<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Repositories\ProductAttributeValueRepository;
use Webkul\Product\Helpers\ProductType;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Core\Repositories\ChannelRepository;

class ProductFlat
{
    /**
     * Attribute codes that can be fill during flat creation.
     *
     * @var string[]
     */
    protected $fillableAttributeCodes = [
        'sku',
        'name',
        'price',
        'weight',
        'status',
    ];

    /**
     * @var array
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
        'checkbox'    => 'text',
    ];

    /**
     * @var array
     */
    protected $flatColumns = [];

    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $productAttributeValueRepository
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeOptionRepository $attributeOptionRepository,
        protected ProductFlatRepository $productFlatRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository
    )
    {
        $this->flatColumns = Schema::getColumnListing('product_flat');
    }

    /**
     * After the attribute is created
     *
     * @param  \Webkul\Attribute\Contracts\Attribute  $attribute
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

        if (in_array($attribute->code, $this->flatColumns)) {
            return;
        }

        Schema::table('product_flat', function (Blueprint $table) use($attribute) {
            $table->{$this->attributeTypeFields[$attribute->type]}($attribute->code)->nullable();

            if (
                $attribute->type == 'select'
                || $attribute->type == 'multiselect'
            ) {
                $table->string($attribute->code . '_label')->nullable();
            }
        });
    }

    /**
     * After the attribute is deleted
     *
     * @param  int  $attributeId
     * @return void
     */
    public function afterAttributeDeleted($attributeId)
    {
        $attribute = $this->attributeRepository->find($attributeId);
        
        if (! in_array(strtolower($attribute->code), $this->flatColumns)) {
            return;
        }

        Schema::table('product_flat', function (Blueprint $table) use($attribute) {
            $table->dropColumn($attribute->code);

            if (
                $attribute->type == 'select'
                || $attribute->type == 'multiselect'
            ) {
                $table->dropColumn($attribute->code . '_label');
            }
        });
        
        $this->productFlatRepository->updateAttributeColumn( $attribute , $this);
    }

    /**
     * Creates product flat
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterProductCreatedUpdated($product)
    {
        $this->createFlat($product);

        if (! ProductType::hasVariants($product->type)) {
            return;
        }

        foreach ($product->variants()->get() as $variant) {
            $this->createFlat($variant, $product);
        }
    }

    /**
     * Creates product flat
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  \Webkul\Product\Contracts\Product  $parentProduct
     * @return void
     */
    public function createFlat($product, $parentProduct = null)
    {
        static $familyAttributes = [];

        static $superAttributes = [];

        if (! array_key_exists($product->attribute_family_id, $familyAttributes)) {
            $familyAttributes[$product->attribute_family_id] = $product->attribute_family->custom_attributes;
        }

        if (
            $parentProduct
            && ! array_key_exists($parentProduct->id, $superAttributes)
        ) {
            $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();
        }

        if (isset($product['channels'])) {
            foreach ($product['channels'] as $channel) {
                $channels[] = $this->getChannel($channel)->code;
            }
        } elseif (isset($parentProduct['channels'])){
            foreach ($parentProduct['channels'] as $channel) {
                $channels[] = $this->getChannel($channel)->code;
            }
        } else {
            $channels[] = core()->getDefaultChannelCode();
        }

        $attributeValues = $product->attribute_values()->get();

        foreach (core()->getAllChannels() as $channel) {
            if (in_array($channel->code, $channels)) {
                foreach ($channel->locales as $locale) {
                    $productFlat = $this->productFlatRepository->updateOrCreate([
                        'product_id' => $product->id,
                        'channel'    => $channel->code,
                        'locale'     => $locale->code,
                    ]);

                    foreach ($familyAttributes[$product->attribute_family_id] as $attribute) {
                        if (
                            (
                                $parentProduct
                                && ! in_array($attribute->code, array_merge($superAttributes[$parentProduct->id], $this->fillableAttributeCodes))
                            )
                            || in_array($attribute->code, ['tax_category_id'])
                            || ! in_array($attribute->code, $this->flatColumns)
                        ) {
                            continue;
                        }

                        if ($attribute->value_per_channel) {
                            if ($attribute->value_per_locale) {
                                $productAttributeValue = $attributeValues
                                    ->where('channel', $channel->code)
                                    ->where('locale', $locale->code)
                                    ->where('attribute_id', $attribute->id)
                                    ->first();
                            } else {
                                $productAttributeValue = $attributeValues
                                    ->where('channel', $channel->code)
                                    ->where('attribute_id', $attribute->id)
                                    ->first();
                            }
                        } else {
                            if ($attribute->value_per_locale) {
                                $productAttributeValue = $attributeValues
                                    ->where('locale', $locale->code)
                                    ->where('attribute_id', $attribute->id)
                                    ->first();
                            } else {
                                $productAttributeValue = $attributeValues
                                    ->where('attribute_id', $attribute->id)
                                    ->first();
                            }
                        }

                        $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]] ?? null;

                        if ($attribute->type == 'select') {
                            $attributeOption = $this->getAttributeOptions($productFlat->{$attribute->code});

                            if ($attributeOption) {
                                if ($attributeOptionTranslation = $attributeOption->translate($locale->code)) {
                                    $productFlat->{$attribute->code . '_label'} = $attributeOptionTranslation->label;
                                } else {
                                    $productFlat->{$attribute->code . '_label'} = $attributeOption->admin_name;
                                }
                            }
                        } elseif ($attribute->type == 'multiselect') {
                            $attributeOptionIds = explode(',', $productFlat->{$attribute->code});

                            if (count($attributeOptionIds)) {
                                $attributeOptions = $this->getAttributeOptions($productFlat->{$attribute->code});

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

                    $productFlat->min_price = $product->getTypeInstance()->getMinimalPrice();

                    $productFlat->max_price = $product->getTypeInstance()->getMaximumPrice();

                    if ($parentProduct) {
                        $parentProductFlat = $this->productFlatRepository->findOneWhere([
                            'product_id' => $parentProduct->id,
                            'channel'    => $channel->code,
                            'locale'     => $locale->code,
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

    /**
     * @param  string  $id
     * @return mixed
     */
    public function getChannel($id)
    {
        static $channels = [];

        if (isset($channels[$id])) {
            return $channels[$id];
        }

        return $channels[$id] = app(ChannelRepository::class)->findOrFail($id);
    }

    /**
     * @param  string  $value
     * @return mixed
     */
    public function getAttributeOptions($value)
    {
        if (! $value) {
            return;
        }

        static $attributeOptions = [];

        if (array_key_exists($value, $attributeOptions)) {
            return $attributeOptions[$value];
        }

        if (is_numeric($value)) {
            return $attributeOptions[$value] = $this->attributeOptionRepository->find($value);
        } else {
            $attributeOptionIds = explode(',', $value);
            
            return $attributeOptions[$value] = $this->attributeOptionRepository->findWhereIn('id', $attributeOptionIds);
        }
    }
}
