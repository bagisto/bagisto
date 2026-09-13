<?php

/**
 * The seeded customer groups a promotion can target, paired with the group a shopper must belong
 * to in order to receive it. A null shopper group means the storefront is browsed as a guest.
 */
sharedDataset('customer groups', [
    'every customer group' => [[1, 2, 3], null],
    'guests only' => [[1], null],
    'general customers only' => [[2], 2],
    'wholesale customers only' => [[3], 3],
]);
