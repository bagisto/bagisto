<?php

namespace Webkul\Product\Product;

use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;
use Webkul\Product\Product\ProductImage;
use Webkul\Product\Product\Price;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;

class ConfigurableOption extends AbstractProduct
{
    /**
     * AttributeOptionRepository object
     *
     * @var array
     */
    protected $attributeOption;

    /**
     * ProductImage object
     *
     * @var array
     */
    protected $productImage;

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
     * @param  Webkul\Product\Product\ProductImage                     $productImage
     * @param  Webkul\Product\Product\Price                            $price
     * @return void
     */
    public function __construct(
        AttributeOption $attributeOption,
        ProductImage $productImage,
        Price $price
    )
    {
        $this->attributeOption = $attributeOption;

        $this->productImage = $productImage;

        $this->price = $price;
    }

    /**
     * Returns the allowed variants
     *
     * @param Product $product
     * @return float
     */
    public function getAllowedProducts($product)
    {
        static $variants = [];

        if(count($variants))
            return $variants;

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
        $options = $this->getOptions($product, $this->getAllowedProducts($product));

        $config = [
            'attributes' => $this->getAttributesData($product, $options),
            'index' => isset($options['index']) ? $options['index'] : [],
            'regular_price' => [
                'formated_price' => core()->currency($this->price->getMinimalPrice($product)),
                'price' => $this->price->getMinimalPrice($product)
            ],
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

        foreach ($this->getAllowedProducts($product) as $variant) {
            $prices[$variant->id] = [
                'regular_price' => [
                    'formated_price' => core()->currency($variant->price),
                    'price' => $variant->price
                ],
                'final_price' => [
                    'formated_price' => core()->currency($this->price->getMinimalPrice($variant)),
                    'price' => $this->price->getMinimalPrice($variant)
                ]
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

        foreach ($this->getAllowedProducts($product) as $variant) {
            $images[$variant->id] = $this->productImage->getGalleryImages($variant);
        }

        return $images;
    }
}