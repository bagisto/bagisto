<?php

return [
    // use the 'code' of the 'attributes' table here to be able to control which attributes
    // should be skipped when doing a copy (admin->catalog->products->copy product).
    // you can also add every relation that should not be copied here to skip them.
    // defaults to none (which means everything is copied).
    'skipAttributesOnCopy' => [

    ],

    // Make the original and source product 'related' via the 'product_relations' table
    'linkProductsOnCopy'   => false,
];