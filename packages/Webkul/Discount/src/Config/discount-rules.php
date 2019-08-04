<?php

return [

    'cart' => [
        'fixed_amount' => 'Webkul\Discount\Actions\Cart\FixedAmount',
        'percent_of_product' => 'Webkul\Discount\Actions\Cart\PercentOfProduct',
        // 'whole_cart_to_fixed' => 'aWebkul\Discount\Actions\Cart\WholeCartToFixed',
        'whole_cart_to_percent' => 'Webkul\Discount\Actions\Cart\WholeCartToPercent'
    ],

    'catalog' => [
        'fixed_amount' => 'Webkul\Discount\Actions\Catalog\FixedAmount',
        'final_price_to_percent' => 'Webkul\Discount\Actions\Catalog\AdjustToPercent',
        'percent_of_original' => 'Webkul\Discount\Actions\Catalog\PercentOfOriginal',
        'to_discount_value' => 'Webkul\Discount\Actions\Catalog\AdjustToDiscountValue'
    ]
];