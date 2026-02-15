<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Support\Str;
use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\SellerTransaction;

class SellerTransactionRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return SellerTransaction::class;
    }

    /**
     * Create a new transaction.
     */
    public function create(array $data)
    {
        $data['transaction_id'] = $data['transaction_id'] ?? 'MP-' . strtoupper(Str::random(10));

        return parent::create($data);
    }

    /**
     * Get seller balance.
     */
    public function getBalance(int $sellerId): float
    {
        $credits = $this->model
            ->where('seller_id', $sellerId)
            ->where('type', 'credit')
            ->sum('base_amount');

        $debits = $this->model
            ->where('seller_id', $sellerId)
            ->where('type', 'debit')
            ->sum('base_amount');

        return (float) ($credits - $debits);
    }
}
