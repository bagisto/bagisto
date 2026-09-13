<?php

/**
 * The two kinds of storefront visitor a checkout step must serve: a guest and a signed-in customer.
 */
sharedDataset('shoppers', [
    'guest' => [false],
    'signed-in customer' => [true],
]);
