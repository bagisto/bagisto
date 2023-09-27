<?php

namespace Webkul\Product\Helpers\Indexers;

use Illuminate\Support\Facades\Schema;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Product\Repositories\ProductFlatRepository;
use Webkul\Product\Helpers\ProductType;

class Flat
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
     * Channels
     *
     * @var array
     */
    protected $channels = [];

    /**
     * Super Attribute Codes
     *
     * @var array
     */
    protected $superAttributeCodes = [];

    /**
     * Family Attributes
     *
     * @var array
     */
    protected $familyAttributes = [];

    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @return void
     */
    public function __construct(
        protected ChannelRepository $channelRepository,
        protected ProductFlatRepository $productFlatRepository
    )
    {
        $this->flatColumns = Schema::getColumnListing('product_flat');
    }

    /**
     * Refresh product flat indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refresh($product)
    {
        $this->updateOrCreate($product);

        if (! ProductType::hasVariants($product->type)) {
            return;
        }

        foreach ($product->variants()->get() as $variant) {
            $this->updateOrCreate($variant, $product);
        }
    }

    /**
     * Creates product flat
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  \Webkul\Product\Contracts\Product  $parentProduct
     * @return void
     */
    public function updateOrCreate($product, $parentProduct = null)
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
                        'product_id'          => $product->id,
                        'channel'             => $channel->code,
                        'locale'              => $locale->code,
                    ], [
                        'type'                => $product->type,
                        'sku'                 => $product->sku,
                        'attribute_family_id' => $product->attribute_family_id,
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
                            || $attribute->code == 'sku'
                        ) {
                            continue;
                        }

                        $productAttributeValues = $attributeValues->where('attribute_id', $attribute->id);

                        if ($attribute->value_per_channel) {
                            if ($attribute->value_per_locale) {
                                $productAttributeValues = $productAttributeValues
                                    ->where('channel', $channel->code)
                                    ->where('locale', $locale->code);
                            } else {
                                $productAttributeValues = $productAttributeValues->where('channel', $channel->code);
                            }
                        } else {
                            if ($attribute->value_per_locale) {
                                $productAttributeValues = $productAttributeValues->where('locale', $locale->code);
                            }
                        }

                        $productAttributeValue = $productAttributeValues->first();

                        $productFlat->{$attribute->code} = $productAttributeValue[$attribute->column_name] ?? null;
                    }

                    if ($parentProduct) {
                        $parentProductFlat = $this->productFlatRepository->findOneWhere([
                            'product_id' => $parentProduct->id,
                            'channel'    => $channel->code,
                            'locale'     => $locale->code,
                        ]);

                        $productFlat->parent_id = $parentProductFlat?->id;
                    }

                    $productFlat->save();
                }
            } else {
                if (request()->route()?->getName() == 'admin.catalog.products.update') {
                    $productFlat = $this->productFlatRepository->findWhere([
                        'product_id' => $product->id,
                        'channel'    => $channel->code,
                    ]);

                    if ($productFlat) {
                        foreach ($productFlat as $productFlatByChannelLocale) {
                            $this->productFlatRepository->delete($productFlatByChannelLocale->id);
                        }
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
        if (array_key_exists($product->attribute_family_id, $this->familyAttributes)) {
            return $this->familyAttributes[$product->attribute_family_id];
        }

        return $this->familyAttributes[$product->attribute_family_id] = $product->attribute_family->custom_attributes;
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

        if (array_key_exists($product->id, $this->superAttributeCodes)) {
            return $this->superAttributeCodes[$product->id];
        }

        return $this->superAttributeCodes[$product->id] = $product->super_attributes()->pluck('code')->toArray();
    }

    /**
     * @param  string  $id
     * @return mixed
     */
    public function getCachedChannel($id)
    {
        if (isset($this->channels[$id])) {
            return $this->channels[$id];
        }

        return $this->channels[$id] = $this->channelRepository->findOrFail($id);
    }
}
