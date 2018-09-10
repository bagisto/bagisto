<?php

namespace Webkul\Product\Product;

use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;
use Webkul\Product\Product\Gallery;
use Webkul\Product\Product\Price;

class ConfigurableOption extends AbstractProduct
{
    /**
     * AttributeOptionRepository object
     *
     * @var array
     */
    protected $attributeOption;

    /**
     * Gallery object
     *
     * @var array
     */
    protected $gallery;

    /**
     * Price object
     *
     * @var array
     */
    protected $price;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository $attributeOption
     * @param  Webkul\Product\Product\Gallery                          $gallery
     * @param  Webkul\Product\Product\Price                            $price
     * @return void
     */
    public function __construct(
        AttributeOption $attributeOption,
        Gallery $gallery,
        Price $price
    )
    {
        $this->attributeOption = $attributeOption;

        $this->gallery = $gallery;

        $this->price = $price;
    }

    /**
     * Returns the allowed variants
     *
     * @param Product $product
     * @return float
     */
    public function getAllowProducts($product)
    {
        $variants = [];

        foreach ($product->variants as $variant) {
            if ($variant->isSaleable()) {
                $variants[] = $variant;
            }
        }
        
        return $variants;
    }

    /**
     * Returns the allowed variants JSON
     *
     * @param Product $product
     * @return float
     */
    public function getConfigurationConfig($product)
    {
        $options = $this->getOptions($product, $this->getAllowProducts($product));

        $config = [
            'attributes' => $this->getAttributesData($product, $options),
            'index' => isset($options['index']) ? $options['index'] : [],
            'variant_prices' => $this->getVariantPrices($product),
            'variant_images' => $this->getVariantImages($product),
            'chooseText' => trans('shop::app.products.choose-option')
        ];

        return $config;
    }

    /**
     * Get allowed attributes
     *
     * @param Product $product
     * @return array
     */
    public function getAllowAttributes($product)
    {
        return $product->super_attributes;
    }

    /**
     * Get Configurable Product Options
     *
     * @param Product $currentProduct
     * @param array $allowedProducts
     * @return array
     */
    public function getOptions($currentProduct, $allowedProducts)
    {
        $options = [];

        $allowAttributes = $this->getAllowAttributes($currentProduct);

        foreach ($allowedProducts as $product) {

            $productId = $product->id;

            foreach ($allowAttributes as $productAttribute) {
                $productAttributeId = $productAttribute->id;

                $attributeValue = $product->{$productAttribute->code};

                $options[$productAttributeId][$attributeValue][] = $productId;

                $options['index'][$productId][$productAttributeId] = $attributeValue;
            }
        }

        return $options;
    }

    /**
     * Get product attributes
     *
     * @param Product $product
     * @param array $options
     * @return array
     */
    public function getAttributesData($product, array $options = [])
    {
        $defaultValues = [];

        $attributes = [];

        foreach ($product->super_attributes as $attribute) {

            $attributeOptionsData = $this->getAttributeOptionsData($attribute, $options);

            if ($attributeOptionsData) {
                $attributeId = $attribute->id;

                $attributes[] = [
                    'id' => $attributeId,
                    'code' => $attribute->code,
                    'label' => $attribute->name,
                    'options' => $attributeOptionsData
                ];
            }
        }

        return $attributes;
    }

    /**
     * @param Attribute $attribute
     * @param array $options
     * @return array
     */
    protected function getAttributeOptionsData($attribute, $options)
    {
        $attributeOptionsData = [];

        foreach ($attribute->options as $attributeOption) {

            $optionId = $attributeOption->id;

            if(isset($options[$attribute->id][$optionId])) {
                $attributeOptionsData[] = [
                    'id' => $optionId,
                    'label' => $attributeOption->label,
                    'products' => $options[$attribute->id][$optionId]
                ];
            }
        }

        return $attributeOptionsData;
    }

    /**
     * Get product prices for configurable variations
     *
     * @param Product $product
     * @return array
     */
    protected function getVariantPrices($product)
    {
        $prices = [];

        foreach ($this->getAllowProducts($product) as $variant) {
            $prices[$variant->id] = [
                'regular_price' => $variant->price,
                'final_price' => $this->price->getMinimalPrice($variant),
            ];
        }

        return $prices;
    }

    /**
     * Get product images for configurable variations
     *
     * @param Product $product
     * @return array
     */
    protected function getVariantImages($product)
    {
        $images = [];

        foreach ($this->getAllowProducts($product) as $variant) {
            $images[$variant->id] = $this->gallery->getImages($variant);
        }

        return $images;
    }
}