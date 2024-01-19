<?php

namespace Webkul\DataTransfer\Helpers\Types\Product;

use Webkul\Product\Repositories\ProductRepository;

class SKUStorage
{
    private const DELIMITER = '|';

    /**
     * Items contains SKU as key and product information as value
     */
    protected array $items = [];

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository)
    {
    }

    /**
     * Get SKU information
     */
    public function init(): void
    {
        $products = $this->productRepository->all([
            'id',
            'type',
            'sku',
            'attribute_family_id',
        ]);

        foreach ($products as $product) {
            $this->set($product->sku, $product->toArray());
        }
    }

    /**
     * Get SKU information
     */
    public function set(string $sku, array $data): self
    {
        $this->items[$sku] = implode(self::DELIMITER, [
            $data['id'],
            $data['type'],
            $data['attribute_family_id'],
        ]);

        return $this;
    }

    /**
     * Check if SKU exists
     */
    public function has(string $sku): bool
    {
        return isset($this->items[$sku]);
    }

    /**
     * Get SKU information
     */
    public function get(string $sku): ?array
    {
        if (! $this->has($sku)) {
            return null;
        }

        $data = explode(self::DELIMITER, $this->items[$sku]);

        return [
            'id'                  => $data[0],
            'type'                => $data[1],
            'attribute_family_id' => $data[2],
        ];
    }
}
