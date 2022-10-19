<?php

namespace Webkul\Product\Helpers\Indexers\Flat;

use Illuminate\Support\Facades\Schema;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Helpers\ProductType;

class Product
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
    protected $flatColumns = [];

    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @param  \Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected AttributeOptionRepository $attributeOptionRepository,
        protected ProductFlatRepository $productFlatRepository
    )
    {
        $this->flatColumns = Schema::getColumnListing('product_flat');
    }

    /**
     * Refresh product indexer indexes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refresh($product)
    {
        $this->updateCreate($product);

        if (! ProductType::hasVariants($product->type)) {
            return;
        }

        foreach ($product->variants()->get() as $variant) {
            $this->updateCreate($variant, $product);
        }
    }

    /**
     * Creates product flat
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  \Webkul\Product\Contracts\Product  $parentProduct
     * @return void
     */
    public function updateCreate($product, $parentProduct = null)
    {
        $familyAttributes = $this->getCachedFamilyAttributes($product);

        $superAttributes = $this->getCachedSuperAttributeCodes($parentProduct);

        $channelCodes = $product['channels'] ?? ($parentProduct['channels'] ?? []);

        if (! empty($channelCodes)) {
            foreach ($channelCodes as $channel) {
                $channels[] = $this->getCachedChannel($channel)->code;
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

                    foreach ($familyAttributes as $attribute) {
                        if (
                            (
                                $parentProduct
                                && ! in_array($attribute->code, array_merge(
                                    $superAttributes,
                                    $this->fillableAttributeCodes
                                ))
                            )
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

                        $productFlat->{$attribute->code} = $productAttributeValue[$attribute->column_name] ?? null;

                        if ($attribute->type == 'select') {
                            $attributeOption = $this->getCachedAttributeOptions($productFlat->{$attribute->code});

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
                                $attributeOptions = $this->getCachedAttributeOptions($productFlat->{$attribute->code});

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
                if (request()->route()?->getName() == 'admin.catalog.products.update') {
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
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return mixed
     */
    public function getCachedFamilyAttributes($product)
    {
        static $attributes = [];

        if (array_key_exists($product->attribute_family_id, $attributes)) {
            return $attributes[$product->attribute_family_id];
        }

        return $attributes[$product->attribute_family_id] = $product->attribute_family->custom_attributes;
    }

    /**
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return mixed
     */
    public function getCachedSuperAttributeCodes($product)
    {
        if (! $product) {
            return [];
        }

        static $attributeCodes = [];

        if (array_key_exists($product->id, $attributeCodes)) {
            return $attributeCodes[$product->id];
        }

        return $attributeCodes[$product->id] = $product->super_attributes()->pluck('code')->toArray();
    }

    /**
     * @param  string  $id
     * @return mixed
     */
    public function getCachedChannel($id)
    {
        static $channels = [];

        if (isset($channels[$id])) {
            return $channels[$id];
        }

        return $channels[$id] = $this->channelRepository->findOrFail($id);
    }

    /**
     * @param  string  $value
     * @return mixed
     */
    public function getCachedAttributeOptions($value)
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
