<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\Seller;

class SellerRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return Seller::class;
    }

    /**
     * Find seller by customer ID.
     */
    public function findByCustomerId(int $customerId): ?object
    {
        return $this->findOneByField('customer_id', $customerId);
    }

    /**
     * Find seller by URL slug.
     */
    public function findByUrl(string $url): ?object
    {
        return $this->findOneByField('url', $url);
    }

    /**
     * Get all approved and active sellers.
     */
    public function getActiveSellers()
    {
        return $this->findWhere([
            'is_approved' => true,
            'status'      => true,
        ]);
    }
}
