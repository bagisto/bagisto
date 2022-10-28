<?php

namespace Webkul\Product\Helpers;

use Webkul\Product\Facades\{ProductImage, ProductVideo};

class ConfigurableOption
{
    /**
     * Returns the allowed variants.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getAllowedProducts($product)
    {
        static $variants = [];

        if (count($variants)) {
            return $variants;
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
                $variants[] = $variant;
            }
        }

        return $variants;
    }

    /**
     * Returns the allowed variants JSON.
     *
     * @param  \Webkul\Product\Models\Product  $product
     * @return array
     */
    public function getConfigurationConfig($product)
    {
        $options = $this->getOptions($product, $this->getAllowedProducts($product));

        $config = [
            'attributes'     => $this->getAttributesData($product, $options),
            'index'          => $options['index'] ?? [],
            'variant_prices' => $this->getVariantPrices($product),
            'variant_images' => $this->getVariantImages($product),
            'variant_videos' => $this->getVariantVideos($product),
            'chooseText'     => trans('shop::app.products.choose-option'),
        ];

        return array_merge($config, $product->getTypeInstance()->getProductPrices());
    }

    /**
     * Get allowed attributes.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getAllowAttributes($product)
    {
        static $productSuperAttributes = [];

        if (isset($productSuperAttributes[$product->id])) {
            return $productSuperAttributes[$product->id];
        }

        return $productSuperAttributes[$product->id] = $product->super_attributes()
            ->with(['translation', 'options', 'options.translation'])
            ->get();
    }

    /**
     * Get configurable product options.
     *
     * @param  \Webkul\Product\Contracts\Product  $currentProduct
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
     * Get product attributes.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array  $options
     * @return array
     */
    public function getAttributesData($product, array $options = [])
    {
        $attributes = [];

        $allowAttributes = $this->getAllowAttributes($product);

        foreach ($allowAttributes as $attribute) {
            $attributes[] = [
                'id'          => $attribute->id,
                'code'        => $attribute->code,
                'label'       => $attribute->name ? $attribute->name : $attribute->admin_name,
                'swatch_type' => $attribute->swatch_type,
                'options'     => $this->getAttributeOptionsData($attribute, $options),
            ];
        }

        return $attributes;
    }

    /**
     * Get attribute options data.
     *
     * @param  \Webkul\Attribute\Contracts\Attribute  $attribute
     * @param  array  $options
     * @return array
     */
    protected function getAttributeOptionsData($attribute, $options)
    {
        $attributeOptionsData = [];

        foreach ($attribute->options as $attributeOption) {
            $optionId = $attributeOption->id;

            if (! isset($options[$attribute->id][$optionId])) {
                continue;
            }
            
            $attributeOptionsData[] = [
                'id'           => $optionId,
                'label'        => $attributeOption->label ? $attributeOption->label : $attributeOption->admin_name,
                'swatch_value' => $attribute->swatch_type == 'image' ? $attributeOption->swatch_value_url : $attributeOption->swatch_value,
                'products'     => $options[$attribute->id][$optionId],
            ];
        }

        return $attributeOptionsData;
    }

    /**
     * Get product prices for configurable variations.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    protected function getVariantPrices($product)
    {
        $prices = [];

        foreach ($this->getAllowedProducts($product) as $variant) {
            $prices[$variant->id] = $variant->getTypeInstance()->getProductPrices();
        }

        return $prices;
    }

    /**
     * Get product images for configurable variations.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    protected function getVariantImages($product)
    {
        $images = [];

        foreach ($this->getAllowedProducts($product) as $variant) {
            $images[$variant->id] = ProductImage::getGalleryImages($variant);
        }

        return $images;
    }

    /**
     * Get product videos for configurable variations.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    protected function getVariantVideos($product)
    {
        $videos = [];

        foreach ($this->getAllowedProducts($product) as $variant) {
            $videos[$variant->id] = ProductVideo::getVideos($variant);
        }

        return $videos;
    }
}
