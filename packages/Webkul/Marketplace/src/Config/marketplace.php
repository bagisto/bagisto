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

    /*
    |--------------------------------------------------------------------------
    | Stripe Connect
    |--------------------------------------------------------------------------
    | stripe_secret: platform secret key used for Connect (accounts/transfers) and
    | seller subscription billing. Falls back to the Bagisto Stripe gateway key
    | (Admin → Configure → Stripe) when STRIPE_CONNECT_SECRET is not set.
    |
    | connect_enabled: when true, payouts can be sent automatically to a seller's
    | connected Stripe account instead of (or alongside) the manual PIX/bank flow.
    */
    'stripe_secret'   => env('STRIPE_CONNECT_SECRET'),
    'connect_enabled' => env('MARKETPLACE_STRIPE_CONNECT', false),
];
