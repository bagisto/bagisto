<?php

namespace Webkul\Product\Helpers;

use Illuminate\Support\Collection;
use Webkul\Attribute\Contracts\Attribute;
use Webkul\Attribute\Contracts\AttributeOption;
use Webkul\ImageCache\TemplateRegistry;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Facades\ProductImage;
use Webkul\Product\Facades\ProductVideo;

class ConfigurableOption
{
    /**
     * Allowed products.
     *
     * @var array
     */
    protected $allowedVariants = [];

    /**
     * Super attributes.
     *
     * @var array
     */
    protected $superAttributes = [];

    /**
     * Returns the allowed variants.
     *
     * @param  Product  $product
     * @return array
     */
    public function getAllowedVariants($product)
    {
        if (count($this->allowedVariants)) {
            return $this->allowedVariants;
        }

        $variantCollection = $product->variants()
            ->with([
                'parent',
                'attribute_values',
                'price_indices',
                'inventory_indices',
                'images',
                'videos',
            ])
            ->get();

        foreach ($variantCollection as $variant) {
            if ($variant->isSaleable()) {
                $this->allowedVariants[] = $variant;
            }
        }

        return $this->allowedVariants;
    }

    /**
     * Returns the allowed variants JSON.
     *
     * @param  \Webkul\Product\Models\Product  $product
     * @return array
     */
    public function getConfigurationConfig($product)
    {
        $options = $this->getOptions($product, $this->getAllowedVariants($product));

        $config = [
            'attributes' => $this->getAttributesData($product, $options),
            'index' => $options['index'] ?? [],
            'variant_prices' => $this->getVariantPrices($product),
            'variant_images' => $this->getVariantImages($product),
            'variant_videos' => $this->getVariantVideos($product),
        ];

        return array_merge($config, $product->getTypeInstance()->getProductPrices());
    }

    /**
     * Get allowed attributes.
     *
     * @param  Product  $product
     * @return Collection
     */
    public function getAllowAttributes($product)
    {
        if (isset($this->superAttributes[$product->id])) {
            return $this->superAttributes[$product->id];
        }

        return $this->superAttributes[$product->id] = $product->super_attributes()
            ->with(['translations', 'options', 'options.translations'])
            ->get();
    }

    /**
     * Get configurable product options.
     *
     * @param  Product  $currentProduct
     * @param  array  $allowedProducts
     * @return array
     */
    public function getOptions($currentProduct, $allowedProducts)
    {
        $options = [];

        $allowAttributes = $this->getAllowAttributes($currentProduct);

        foreach ($allowedProducts as $product) {
            foreach ($allowAttributes as $productAttribute) {
                $productAttributeId = $productAttribute->id;

                $attributeValue = $product->{$productAttribute->code};

                $options[$productAttributeId][$attributeValue][] = $product->id;

                $options['index'][$product->id][$productAttributeId] = $attributeValue;
            }
        }

        return $options;
    }

    /**
     * Get product attributes, with the storefront's sized swatch image urls unless they are left out,
     * in which case an image swatch's value is its stored file.
     *
     * @param  Product  $product
     * @return array
     */
    public function getAttributesData($product, array $options = [], bool $withImageUrls = true)
    {
        $attributes = [];

        $allowAttributes = $this->getAllowAttributes($product);

        foreach ($allowAttributes as $attribute) {
            $attributes[] = [
                'id' => $attribute->id,
                'code' => $attribute->code,
                'label' => $attribute->name ? $attribute->name : $attribute->admin_name,
                'swatch_type' => $attribute->swatch_type,
                'options' => $this->getAttributeOptionsData($attribute, $options, $withImageUrls),
            ];
        }

        return $attributes;
    }

    /**
     * Get attribute options data.
     *
     * @param  Attribute  $attribute
     * @param  array  $options
     * @return array
     */
    protected function getAttributeOptionsData($attribute, $options, bool $withImageUrls = true)
    {
        $attributeOptionsData = [];

        foreach ($attribute->options->sortBy('sort_order') as $attributeOption) {
            $optionId = $attributeOption->id;

            if (! isset($options[$attribute->id][$optionId])) {
                continue;
            }

            $optionData = [
                'id' => $optionId,
                'label' => $attributeOption->label ? $attributeOption->label : $attributeOption->admin_name,
                'swatch_value' => $attribute->swatch_type == 'image' ? $attributeOption->swatch_value_url : $attributeOption->swatch_value,
                'swatch_alt' => $attributeOption->swatch_alt,
                'products' => $options[$attribute->id][$optionId],
            ];

            if ($withImageUrls) {
                $optionData = $this->withSwatchImage($optionData, $attribute, $attributeOption);
            }

            $attributeOptionsData[] = $optionData;
        }

        return $attributeOptionsData;
    }

    /**
     * Give an option's data the storefront's sized urls of its image swatch, its value becoming the small one.
     *
     * @param  Attribute  $attribute
     * @param  AttributeOption  $attributeOption
     */
    protected function withSwatchImage(array $optionData, $attribute, $attributeOption): array
    {
        $swatchImage = $this->getSwatchImage($attribute, $attributeOption);

        return array_merge($optionData, [
            'swatch_value' => $swatchImage['small_image_url'] ?? $optionData['swatch_value'],
            'swatch_image' => $swatchImage,
        ]);
    }

    /**
     * Get an image swatch's urls through the core sizes and the templates the current theme lists for
     * swatch images, or null for a color or text swatch.
     *
     * @param  Attribute  $attribute
     * @param  AttributeOption  $attributeOption
     */
    protected function getSwatchImage($attribute, $attributeOption): ?array
    {
        if (
            $attribute->swatch_type != 'image'
            || ! $attributeOption->swatch_value
        ) {
            return null;
        }

        return image_urls($attributeOption->swatch_value, TemplateRegistry::SWATCH_IMAGES) + [
            'alt' => $attributeOption->swatch_alt ?: ($attributeOption->label ?: $attributeOption->admin_name),
        ];
    }

    /**
     * Get product prices for configurable variations.
     *
     * @param  Product  $product
     * @return array
     */
    protected function getVariantPrices($product)
    {
        $prices = [];

        foreach ($this->getAllowedVariants($product) as $variant) {
            $prices[$variant->id] = $variant->getTypeInstance()->getProductPrices();
        }

        return $prices;
    }

    /**
     * Get product images for configurable variations.
     *
     * @param  Product  $product
     * @return array
     */
    protected function getVariantImages($product)
    {
        $images = [];

        foreach ($this->getAllowedVariants($product) as $variant) {
            $images[$variant->id] = ProductImage::getGalleryImages($variant);
        }

        return $images;
    }

    /**
     * Get product videos for configurable variations.
     *
     * @param  Product  $product
     * @return array
     */
    protected function getVariantVideos($product)
    {
        $videos = [];

        foreach ($this->getAllowedVariants($product) as $variant) {
            $videos[$variant->id] = ProductVideo::getVideos($variant);
        }

        return $videos;
    }
}
