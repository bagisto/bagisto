<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Webkul\Product\Repositories\ProductRepository;

class ProductIndexCheck extends AbstractCheck
{
    /**
     * Seconds a new product is given to be indexed before it counts as missed.
     */
    public const GRACE = 900;

    /**
     * Create a new check instance.
     */
    public function __construct(protected ProductRepository $productRepository) {}

    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'product-index';
    }

    /**
     * Whether any product has gone without a price index for longer than the grace period.
     */
    public function failing(): bool
    {
        return $this->productRepository->hasUnindexedPrices(now()->subSeconds(self::GRACE));
    }
}
