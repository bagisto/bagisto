<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature flag — set MARKETPLACE_ENABLED=true in .env to activate
    |--------------------------------------------------------------------------
    */
    'enabled' => env('MARKETPLACE_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Default commission rate (%) applied when no plan or seller override
    |--------------------------------------------------------------------------
    */
    'default_commission_rate' => env('MARKETPLACE_DEFAULT_COMMISSION', 10),

    /*
    |--------------------------------------------------------------------------
    | Trial period in days for new sellers
    |--------------------------------------------------------------------------
    */
    'trial_days' => env('MARKETPLACE_TRIAL_DAYS', 14),

    /*
    |--------------------------------------------------------------------------
    | Minimum payout amount
    |--------------------------------------------------------------------------
    */
    'min_payout_amount' => env('MARKETPLACE_MIN_PAYOUT', 50),

    /*
    |--------------------------------------------------------------------------
    | Default currency for payouts
    |--------------------------------------------------------------------------
    */
    'default_currency' => env('MARKETPLACE_CURRENCY', 'BRL'),

    /*
    |--------------------------------------------------------------------------
    | Auto-approve sellers (false = requires admin approval)
    |--------------------------------------------------------------------------
    */
    'auto_approve_sellers' => env('MARKETPLACE_AUTO_APPROVE_SELLERS', false),

    /*
    |--------------------------------------------------------------------------
    | Auto-approve seller products (false = requires admin approval)
    |--------------------------------------------------------------------------
    */
    'auto_approve_products' => env('MARKETPLACE_AUTO_APPROVE_PRODUCTS', false),
];
