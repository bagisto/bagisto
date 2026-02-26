<?php

namespace Webkul\Product\DataTypes;

class CartItemValidationResult
{
    /**
     * @var bool
     */
    private $cartIsInvalid = false;

    /**
     * @var bool
     */
    private $itemIsInactive = false;

    /**
     * Function to check if cart is invalid
     */
    public function isCartInvalid(): bool
    {
        return $this->cartIsInvalid;
    }

    /**
     * Function to check if item is inactive
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
    }

    /**
     * Function to set if cart is invalid
     */
    public function cartIsInvalid(): void
    {
        $this->cartIsInvalid = true;
    }
}
