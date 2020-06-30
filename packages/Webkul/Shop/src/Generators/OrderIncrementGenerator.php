<?php


namespace Webkul\Shop\Generators;


interface OrderIncrementGenerator
{
    /**
     * create and return the next increment id for an order
     *
     * @return string
     */
    public static function generate(): string;
}