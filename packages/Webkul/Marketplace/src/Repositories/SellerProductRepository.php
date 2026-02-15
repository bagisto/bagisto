<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\SellerProduct;

class SellerProductRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return SellerProduct::class;
    }

    /**
     * Get products for a seller.
     */
    public function getSellerProducts(int $sellerId)
    {
        return $this->findByField('seller_id', $sellerId);
    }

    /**
     * Get approved products for a seller.
     */
    public function getApprovedProducts(int $sellerId)
    {
        return $this->findWhere([
            'seller_id'   => $sellerId,
            'is_approved' => true,
        ]);
    }

    /**
     * Find the seller for a product.
     */
    public function findSellerByProduct(int $productId): ?object
    {
        $sellerProduct = $this->findOneByField('product_id', $productId);

        return $sellerProduct?->seller;
    }
}
