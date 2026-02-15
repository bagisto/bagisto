<?php

namespace Webkul\Marketplace\Tests\Concerns;

use Webkul\Customer\Models\Customer;
use Webkul\Faker\Helpers\Customer as CustomerFaker;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Models\SellerOrder;
use Webkul\Marketplace\Models\SellerProduct;
use Webkul\Marketplace\Models\SellerReview;
use Webkul\Marketplace\Models\SellerTransaction;

trait MarketplaceTestBench
{
    /**
     * Create a seller with an associated customer.
     */
    public function createSeller(array $attributes = []): Seller
    {
        $customer = (new CustomerFaker)->factory()->create();

        return Seller::factory()->create(array_merge([
            'customer_id' => $customer->id,
        ], $attributes));
    }

    /**
     * Create an unapproved seller.
     */
    public function createUnapprovedSeller(array $attributes = []): Seller
    {
        return $this->createSeller(array_merge([
            'is_approved' => false,
        ], $attributes));
    }

    /**
     * Create an inactive seller.
     */
    public function createInactiveSeller(array $attributes = []): Seller
    {
        return $this->createSeller(array_merge([
            'status' => false,
        ], $attributes));
    }

    /**
     * Create a seller product.
     */
    public function createSellerProduct(Seller $seller, array $attributes = []): SellerProduct
    {
        return SellerProduct::create(array_merge([
            'seller_id'   => $seller->id,
            'product_id'  => 1,
            'is_approved' => true,
            'condition'   => 'new',
            'price'       => 99.99,
            'description' => 'Test product listing',
        ], $attributes));
    }

    /**
     * Create a seller order.
     */
    public function createSellerOrder(Seller $seller, array $attributes = []): SellerOrder
    {
        return SellerOrder::create(array_merge([
            'seller_id'             => $seller->id,
            'order_id'              => 1,
            'base_sub_total'        => 100.0000,
            'base_grand_total'      => 100.0000,
            'base_commission'       => 10.0000,
            'base_seller_total'     => 90.0000,
            'commission_percentage' => 10.00,
            'status'                => 'pending',
        ], $attributes));
    }

    /**
     * Create a seller transaction.
     */
    public function createSellerTransaction(Seller $seller, array $attributes = []): SellerTransaction
    {
        return SellerTransaction::create(array_merge([
            'seller_id'   => $seller->id,
            'transaction_id' => 'MP-TEST' . strtoupper(\Illuminate\Support\Str::random(6)),
            'type'        => 'credit',
            'amount'      => 100.0000,
            'base_amount' => 100.0000,
            'comment'     => 'Test transaction',
            'method'      => 'manual',
        ], $attributes));
    }

    /**
     * Create a seller review.
     */
    public function createSellerReview(Seller $seller, ?Customer $customer = null, array $attributes = []): SellerReview
    {
        $customer = $customer ?? (new CustomerFaker)->factory()->create();

        return SellerReview::create(array_merge([
            'seller_id'   => $seller->id,
            'customer_id' => $customer->id,
            'rating'      => 5,
            'title'       => 'Great seller',
            'comment'     => 'Excellent service and fast shipping.',
            'status'      => 'approved',
        ], $attributes));
    }

    /**
     * Login as a customer who is a seller.
     */
    public function loginAsSeller(Seller $seller): Seller
    {
        $this->actingAs($seller->customer, 'customer');

        return $seller;
    }
}
