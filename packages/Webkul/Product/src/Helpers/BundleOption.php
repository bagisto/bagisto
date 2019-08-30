<?php

namespace Webkul\Product\Helpers;

/**
 * Bundle Option Helper
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class BundleOption extends AbstractProduct
{
    /**
     * Product
     *
     * @var Product
     */
    protected $product;

    /**
     * Returns bundle option config
     *
     * @param Product $product
     * @return array
     */
    public function getBundleConfig($product)
    {
        $this->product = $product;

        return [
            'options' => $this->getOptions()
        ];
    }

    /**
     * Returns bundle options
     *
     * @return array
     */
    public function getOptions()
    {
        $options = [];

        foreach ($this->product->bundle_options as $option) {
            $options[$option->id] = $this->getOptionItemData($option);
        }

        return $options;
    }

    /**
     * Get formed data from bundle option
     *
     * @param ProductBundleOption $option
     * @return array
     */
    private function getOptionItemData($option)
    {
        return [
            'id' => $option->id,
            'label' => $option->label,
            'type' => $option->type,
            'products' => $this->getOptionProducts($option),
            'sort_order' => $option->sort_order
        ];
    }

    /**
     * Get formed data from bundle option product
     * 
     * @param ProductBundleOption $option
     * @return array
     */
    private function getOptionProducts($option)
    {
        $products = [];

        foreach ($option->bundle_option_products as $index => $bundleOptionProduct) {
            $products[$bundleOptionProduct->id] = [
                'id' => $bundleOptionProduct->id,
                'qty' => $bundleOptionProduct->qty,
                'price' => $this->getProductPrices($bundleOptionProduct->product),
                'name' => $bundleOptionProduct->product->name,
                'product_id' => $bundleOptionProduct->product_id,
                'is_default' => $bundleOptionProduct->is_default
            ];
        }

        return $products;
    }

    /**
     * Returns bundle product prices
     *
     * @param Product $product
     * @return array
     */
    public function getProductPrices($product)
    {
        return [
            'regular_price' => [
                'price' => core()->convertPrice($product->price),
                'formated_price' => core()->currency($product->price)
            ],
            'final_price' => [
                'price' => core()->convertPrice($product->getTypeInstance()->getMinimalPrice()),
                'formated_price' => core()->currency($product->getTypeInstance()->getMinimalPrice())
            ]
        ];
    }
}