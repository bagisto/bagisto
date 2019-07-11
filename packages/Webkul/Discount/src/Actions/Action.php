<?php

namespace Webkul\Discount\Actions;

abstract class Action
{
    abstract public function calculate($rule, $item, $cart);

    abstract public function calculateOnShipping($cart);
}