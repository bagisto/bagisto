<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\SellerProduct;

class SellerProductRepository extends Repository
{
    public function model(): string
    {
        return SellerProduct::class;
    }

    public function findBySeller(int $sellerId): object
    {
        return $this->model->where('seller_id', $sellerId)->with('product')->get();
    }

    public function findByProduct(int $productId): ?object
    {
        return $this->model->where('product_id', $productId)->with('seller')->first();
    }

    public function approve(int $id): bool
    {
        return (bool) $this->update(['status' => 'approved'], $id);
    }
}
