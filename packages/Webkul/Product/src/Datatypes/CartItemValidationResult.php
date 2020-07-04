<?php

namespace Webkul\Product\Datatypes;

class CartItemValidationResult
{
    /** @var bool $cartIsDirty */
    private $cartIsDirty = false;

    /** @var bool $itemIsInactive */
    private $itemIsInactive = false;

    /**
     * Function to check if cart is dirty
     * (price has been changed, product was disabled and so on)
     *
     * @return bool
     */
    public function isCartDirty(): bool
    {
        return $this->cartIsDirty;
    }

    /**
     * Function to check if item is inactive
     *
     * @return bool
     */
    public function isItemInactive(): bool
    {
        return $this->itemIsInactive;
    }

    /**
     * Function to set if item is inactive
     */
    public function itemIsInactive(): void
    {
        $this->itemIsInactive = true;
        $this->cartIsDirty = true;
    }

    /**
     * Function to set if cart is dirty
     */
    public function cartIsDirty(): void
    {
        $this->cartIsDirty = true;
    }
}
