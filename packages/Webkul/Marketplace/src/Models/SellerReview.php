<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\Marketplace\Contracts\SellerReview as SellerReviewContract;

class SellerReview extends Model implements SellerReviewContract
{
    protected $table = 'marketplace_seller_reviews';

    protected $fillable = [
        'seller_id',
        'customer_id',
        'rating',
        'title',
        'comment',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get the seller.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProxy::modelClass());
    }

    /**
     * Get the customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }
}
