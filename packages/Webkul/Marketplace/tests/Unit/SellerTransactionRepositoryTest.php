<?php

use Webkul\Marketplace\Repositories\SellerTransactionRepository;

it('auto-generates a transaction id on create', function () {
    $seller = $this->createSeller();

    $repo = app(SellerTransactionRepository::class);

    $transaction = $repo->create([
        'seller_id'   => $seller->id,
        'type'        => 'credit',
        'amount'      => 50.0000,
        'base_amount' => 50.0000,
        'method'      => 'manual',
    ]);

    expect($transaction->transaction_id)->toStartWith('MP-')
        ->and(strlen($transaction->transaction_id))->toBe(13);
});

it('uses provided transaction id if given', function () {
    $seller = $this->createSeller();

    $repo = app(SellerTransactionRepository::class);

    $transaction = $repo->create([
        'seller_id'      => $seller->id,
        'transaction_id' => 'CUSTOM-TXN-001',
        'type'           => 'credit',
        'amount'         => 75.0000,
        'base_amount'    => 75.0000,
        'method'         => 'bank_transfer',
    ]);

    expect($transaction->transaction_id)->toBe('CUSTOM-TXN-001');
});

it('calculates seller balance from credits and debits', function () {
    $seller = $this->createSeller();

    $repo = app(SellerTransactionRepository::class);

    // Initially zero balance
    expect($repo->getBalance($seller->id))->toBe(0.0);

    // Add credits
    $repo->create([
        'seller_id'   => $seller->id,
        'type'        => 'credit',
        'amount'      => 200.0000,
        'base_amount' => 200.0000,
        'method'      => 'manual',
    ]);

    $repo->create([
        'seller_id'   => $seller->id,
        'type'        => 'credit',
        'amount'      => 100.0000,
        'base_amount' => 100.0000,
        'method'      => 'manual',
    ]);

    // Add a debit
    $repo->create([
        'seller_id'   => $seller->id,
        'type'        => 'debit',
        'amount'      => 50.0000,
        'base_amount' => 50.0000,
        'method'      => 'payout',
    ]);

    // Balance should be 200 + 100 - 50 = 250
    expect($repo->getBalance($seller->id))->toBe(250.0);
});

it('returns zero balance when no transactions exist', function () {
    $seller = $this->createSeller();

    expect(app(SellerTransactionRepository::class)->getBalance($seller->id))->toBe(0.0);
});
