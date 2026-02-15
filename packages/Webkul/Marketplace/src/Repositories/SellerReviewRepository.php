<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\SellerReview;

class SellerReviewRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return SellerReview::class;
    }

    /**
     * Get approved reviews for a seller.
     */
    public function getApprovedReviews(int $sellerId)
    {
        return $this->findWhere([
            'seller_id' => $sellerId,
            'status'    => 'approved',
        ]);
    }
}
