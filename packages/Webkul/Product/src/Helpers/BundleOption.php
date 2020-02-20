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

        usort ($options, function($a, $b) {
            if ($a['sort_order'] == $b['sort_order']) {
                return 0;
            }

            return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
        });

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
            'id'          => $option->id,
            'label'       => $option->label,
            'type'        => $option->type,
            'is_required' => $option->is_required,
            'products'    => $this->getOptionProducts($option),
            'sort_order'  => $option->sort_order
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
                'id'         => $bundleOptionProduct->id,
                'qty'        => $bundleOptionProduct->qty,
                'price'      => $bundleOptionProduct->product->getTypeInstance()->getProductPrices(),
                'name'       => $bundleOptionProduct->product->name,
                'product_id' => $bundleOptionProduct->product_id,
                'is_default' => $bundleOptionProduct->is_default,
                'sort_order' => $bundleOptionProduct->sort_order
            ];
        }

        usort ($products, function($a, $b) {
            if ($a['sort_order'] == $b['sort_order']) {
                return 0;
            }

            return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
        });

        return $products;
    }
}