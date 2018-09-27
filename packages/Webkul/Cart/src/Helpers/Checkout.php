<?php

namespace Webkul\Cart\Helpers;

use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;

class Checkout
{
    /**
     * AttributeOptionRepository object
     *
     * @var array
     */
    protected $attributeOption;

    /**
     * Create a new helper instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeOptionRepository $attributeOption
     * @return void
     */
    public function __construct(AttributeOption $attributeOption)
    {
        $this->attributeOption = $attributeOption;
    }

    /**
     * Returns the allowed variants
     *
     * @param CartItem $item
     * @return array
     */
    public function getItemOptionDetails($item)
    {
        $data = [];

        foreach ($item->product->super_attributes as $attribute) {

        }

        return $data;
    }
}