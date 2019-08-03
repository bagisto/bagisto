<?php

namespace Webkul\Discount\Actions;

abstract class Action
{
    abstract public function calculate($rule);

    abstract public function calculateOnShipping();
}