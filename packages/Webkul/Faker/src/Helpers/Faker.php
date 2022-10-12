<?php

namespace Webkul\Faker\Helpers;

class Faker
{
    /**
     * Containes faker classes.
     *
     * @var array
     */
    protected $types = [
        'product'  => Product::Class,
        'category' => Category::Class,
        'customer' => Customer::Class,
    ];

    /**
     * Fake data
     *
     * @params  string  $type
     * @params  integer  $count
     * @return void
     */
    public function fake(string $type = 'product', $count = 1)
    {
        app($this->types[$type])->create($count);
    }
}