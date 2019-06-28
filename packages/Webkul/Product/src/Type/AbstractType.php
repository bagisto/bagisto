<?php

namespace Webkul\Product\Type;

/**
 * Abstract class Type
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
abstract class AbstractType
{
    /**
     * Product model instance
     *
     * @var Product
     */
    protected $product;

    /**
     * Specify type instance product
     *
     * @param   Product $product
     * @return  AbstractType
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Return true if this product type is saleable
     *
     * @return array
     */
    abstract public function isSaleable();

    /**
     * Return true if this product can have inventory
     *
     * @return array
     */
    abstract public function isStockable();

    /**
     * Retrieve product attributes
     *
     * @param Group $group
     * @param bool  $skipSuperAttribute
     * @return Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        if ($skipSuperAttribute)
            $this->skipAttributes = array_merge($this->product->super_attributes->pluck('code')->toArray(), $this->skipAttributes);

        if (! $group)
            return $this->product->attribute_family->custom_attributes()->whereNotIn('attributes.code', $this->skipAttributes)->get();

        return $group->custom_attributes()->whereNotIn('code', $this->skipAttributes)->get();
    }

    /**
     * Returns additional views
     *
     * @return array
     */
    public function getAdditionalViews()
    {
        return $this->additionalViews;
    }
}
?>