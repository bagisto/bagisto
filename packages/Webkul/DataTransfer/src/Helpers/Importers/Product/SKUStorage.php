<?php

namespace Webkul\DataTransfer\Helpers\Importers\Product;

use Illuminate\Support\Arr;
use Webkul\Product\Repositories\ProductRepository;

class SKUStorage
{
    /**
     * Delimiter for SKU information
     */
    private const DELIMITER = '|';

    /**
     * Items contains SKU as key and product information as value
     */
    protected array $items = [];

    /**
     * Columns which will be selected from database
     */
    protected array $selectColumns = [
        'id',
        'type',
        'sku',
        'attribute_family_id',
    ];

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository) {}

    /**
     * Initialize storage
     */
    public function init(): void
    {
        $this->items = [];

        $this->load();
    }

    /**
     * Load the SKU
     */
    public function load(array $skus = []): void
    {
        if (empty($skus)) {
            $products = $this->productRepository->all($this->selectColumns);
        } else {
            $products = $this->productRepository->findWhereIn('sku', $skus, $this->selectColumns);
        }

        foreach ($products as $product) {
            $this->set($product->sku, [
                'id'                  => $product->id,
                'type'                => $product->type,
                'attribute_family_id' => $product->attribute_family_id,
            ]);
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

    /**
     * Return SKU filtered by product type
     */
    public function getByType(string $type): ?array
    {
        $result = Arr::where($this->items, function (string $row, string $key) use ($type) {
            return str_contains($row, '|'.$type.'|');
        });

        return $result;
    }

    /**
     * Is storage is empty
     */
    public function isEmpty(): int
    {
        return empty($this->items);
    }
}
