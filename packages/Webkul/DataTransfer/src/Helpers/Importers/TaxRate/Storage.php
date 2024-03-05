<?php

namespace Webkul\DataTransfer\Helpers\Importers\TaxRate;

use Webkul\Tax\Repositories\TaxRateRepository;

class Storage
{
    /**
     * Items contains identifier as key and product information as value
     */
    protected array $items = [];

    /**
     * Columns which will be selected from database
     */
    protected array $selectColumns = [
        'id',
        'identifier',
    ];

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(protected TaxRateRepository $taxRateRepository)
    {
    }

    /**
     * Initialize storage
     */
    public function init(): void
    {
        $this->items = [];

        $this->load();
    }

    /**
     * Load the identifiers
     */
    public function load(array $identifiers = []): void
    {
        if (empty($identifiers)) {
            $taxRates = $this->taxRateRepository->all($this->selectColumns);
        } else {
            $taxRates = $this->taxRateRepository->findWhereIn('identifier', $identifiers, $this->selectColumns);
        }

        foreach ($taxRates as $taxRate) {
            $this->set($taxRate->identifier, $taxRate->id);
        }
    }

    /**
     * Get identifier information
     */
    public function set(string $identifier, int $id): self
    {
        $this->items[$identifier] = $id;

        return $this;
    }

    /**
     * Check if identifier exists
     */
    public function has(string $identifier): bool
    {
        return isset($this->items[$identifier]);
    }

    /**
     * Get identifier information
     */
    public function get(string $identifier): ?int
    {
        if (! $this->has($identifier)) {
            return null;
        }

        return $this->items[$identifier];
    }

    /**
     * Is storage is empty
     */
    public function isEmpty(): int
    {
        return empty($this->items);
    }
}
