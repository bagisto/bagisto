<?php

return [

    'cart' => [
        'percent_of_product' => 'Webkul\Discount\Actions\Cart\PercentOfProduct',
        'fixed_amount' => 'Webkul\Discount\Actions\Cart\FixedAmount',
        'whole_cart_to_fixed' => 'Webkul\Discount\Actions\Cart\WholeCartToFixed',
        'whole_cart_to_percent' => 'Webkul\Discount\Actions\Cart\WholeCartToPercent'
    ],

    'catalog' => [
        'percent_of_original' => 'Webkul\Discount\Actions\Catalog\PercentOfOriginal',
        'fixed_amount' => 'Webkul\Discount\Actions\Catalog\FixedAmount',
        'final_price_to_percent' => 'Webkul\Discount\Actions\Catalog\AdjustToPercent',
        'to_discount_value' => 'Webkul\Discount\Actions\Catalog\AdjustToDiscountValue'
    ]
];