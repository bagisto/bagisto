<?php

use Webkul\Product\Models\Product;

return [
    // use the 'code' of the 'attributes' table here to be able to control which attributes
    // should be skipped when doing a copy (admin->catalog->products->copy product).
    // you can also add every relation that should not be copied here to skip them.
    // defaults to none (which means everything is copied).
    'skipAttributesOnCopy' => [
    ],

    // Make the original and source product 'related' via the 'product_relations' table
    'linkProductsOnCopy'   => false,

    // Ability to set a global callable that defines if a product is saleable.
    // Return neither true nor false but null by default to not interrupt the default chain that
    // defines if a product is saleable. It depends on the isSaleable() method of the product
    // type if this callable is obeyed.
    'isSaleable'           => null,
];
