<?php

return [
    /**
     * Skip attribute during product copy.
     *
     * Supported Relations: ['categories', 'inventories', 'customer_group_prices', 'images', 'videos', 'product_relations']
     *
     * Support Attributes: All Attributes (Example: 'sku', 'product_number', etc)
     */
    'copy' => [
        'skip_attributes' => [],
    ],
];
