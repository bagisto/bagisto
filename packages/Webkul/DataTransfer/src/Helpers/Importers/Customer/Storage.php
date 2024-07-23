<?php

namespace Webkul\DataTransfer\Helpers\Importers\Customer;

use Webkul\Customer\Repositories\CustomerRepository;

class Storage
{
    /**
     * Items contains email as key and product information as value
     */
    protected array $items = [];

    /**
     * Columns which will be selected from database
     */
    protected array $selectColumns = [
        'id',
        'email',
    ];

    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(protected CustomerRepository $customerRepository) {}

    /**
     * Initialize storage
     */
    public function init(): void
    {
        $this->items = [];

        $this->load();
    }

    /**
     * Load the Emails
     */
    public function load(array $emails = []): void
    {
        if (empty($emails)) {
            $customers = $this->customerRepository->all($this->selectColumns);
        } else {
            $customers = $this->customerRepository->findWhereIn('email', $emails, $this->selectColumns);
        }

        foreach ($customers as $customer) {
            $this->set($customer->email, $customer->id);
        }
    }

    /**
     * Get email information
     */
    public function set(string $email, int $id): self
    {
        $this->items[$email] = $id;

        return $this;
    }

    /**
     * Check if email exists
     */
    public function has(string $email): bool
    {
        return isset($this->items[$email]);
    }

    /**
     * Get email information
     */
    public function get(string $email): ?int
    {
        if (! $this->has($email)) {
            return null;
        }

        return $this->items[$email];
    }

    /**
     * Is storage is empty
     */
    public function isEmpty(): int
    {
        return empty($this->items);
    }
}
