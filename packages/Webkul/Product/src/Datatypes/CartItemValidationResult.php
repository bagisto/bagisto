<?php

namespace Webkul\Product\Datatypes;

class CartItemValidationResult
{
    /** @var bool $cartIsDirty */
    private $cartIsDirty = false;

    /** @var bool $itemIsInactive */
    private $itemIsInactive = false;

    /**
     * @return bool
     */
    public function isCartDirty(): bool
    {
        return $this->cartIsDirty;
    }

    /**
     * @return bool
     */
    public function isItemInactive(): bool
    {
        return $this->itemIsInactive;
    }

    public function itemIsInactive(): void
    {
        $this->itemIsInactive = true;
        $this->cartIsDirty = true;
    }

    public function cartIsDirty(): void
    {
        $this->cartIsDirty = true;
    }
}
