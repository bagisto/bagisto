<?php

namespace Webkul\Discount\Actions\Catalog;

abstract class Action
{
    abstract public function calculate($rule, $price);
}