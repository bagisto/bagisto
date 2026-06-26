<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\Seller;
use Webkul\Marketplace\Enums\SellerStatus;

class SellerRepository extends Repository
{
    public function model(): string
    {
        return Seller::class;
    }

    public function findByCustomer(int $customerId): ?object
    {
        return $this->model->where('customer_id', $customerId)->first();
    }

    public function findByShopUrl(string $shopUrl): ?object
    {
        return $this->model->where('shop_url', $shopUrl)->first();
    }

    public function approve(int $sellerId): bool
    {
        return (bool) $this->update(['status' => SellerStatus::Approved], $sellerId);
    }

    public function suspend(int $sellerId): bool
    {
        return (bool) $this->update(['status' => SellerStatus::Suspended], $sellerId);
    }

    public function featured(): object
    {
        return $this->model
            ->where('status', SellerStatus::Approved)
            ->where('is_featured', true)
            ->get();
    }
}
