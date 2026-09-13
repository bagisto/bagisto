<?php

/**
 * Every product type the storefront can sell, keyed by the type code.
 */
sharedDataset('product types', [
    'simple' => ['simple'],
    'virtual' => ['virtual'],
    'downloadable' => ['downloadable'],
    'configurable' => ['configurable'],
    'grouped' => ['grouped'],
    'bundle' => ['bundle'],
]);

/**
 * The product types that need a shipping address and a shipping method before an order can be placed.
 */
sharedDataset('stockable product types', [
    'simple' => ['simple'],
    'configurable' => ['configurable'],
    'grouped' => ['grouped'],
    'bundle' => ['bundle'],
]);

/**
 * The product types that skip shipping altogether.
 */
sharedDataset('non-stockable product types', [
    'virtual' => ['virtual'],
    'downloadable' => ['downloadable'],
]);
