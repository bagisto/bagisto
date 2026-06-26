<?php

return [
    'admin' => [
        'acl' => [
            'marketplace'   => 'Marketplace',
            'sellers'       => 'Sellers',
            'view'          => 'View',
            'approve'       => 'Approve',
            'commissions'   => 'Commissions',
            'payouts'       => 'Payouts',
            'subscriptions' => 'Subscriptions',
        ],
        'layouts' => [
            'sidebar' => [
                'marketplace'   => 'Marketplace',
                'sellers'       => 'Sellers',
                'commissions'   => 'Commissions',
                'subscriptions' => 'Subscription Plans',
                'payouts'       => 'Payouts',
            ],
        ],
        'sellers' => [
            'index' => [
                'title'   => 'Sellers',
                'add-btn' => 'Add Seller',
            ],
            'approve-success' => 'Seller approved successfully.',
            'suspend-success' => 'Seller suspended successfully.',
        ],
        'commissions' => [
            'index' => [
                'title' => 'Commissions',
            ],
            'paid-success' => 'Commission marked as paid.',
        ],
        'payouts' => [
            'index' => [
                'title' => 'Payout Requests',
            ],
            'approve-success' => 'Payout approved successfully.',
            'reject-success'  => 'Payout rejected.',
        ],
        'subscriptions' => [
            'index' => [
                'title'   => 'Subscription Plans',
                'add-btn' => 'Add Plan',
            ],
            'create'         => ['title' => 'Create Subscription Plan'],
            'edit'           => ['title' => 'Edit Subscription Plan'],
            'create-success' => 'Subscription plan created successfully.',
            'update-success' => 'Subscription plan updated successfully.',
            'delete-success' => 'Subscription plan deleted.',
        ],
    ],
    'seller' => [
        'auth' => [
            'register'          => 'Become a Seller',
            'login-required'    => 'You need to log in before registering as a seller.',
            'already-registered' => 'You already have a seller account.',
            'register-success'  => 'Your seller account has been submitted for review.',
        ],
        'dashboard' => [
            'title'           => 'Seller Dashboard',
            'register-first'  => 'Please complete your seller registration first.',
            'pending-earnings' => 'Pending Earnings',
            'total-earnings'   => 'Total Earnings',
            'recent-orders'    => 'Recent Orders',
        ],
        'products' => [
            'title'          => 'My Products',
            'add-btn'        => 'List a Product',
            'already-listed' => 'This product is already listed in your store.',
            'submit-success' => 'Product submitted for approval.',
        ],
        'orders' => [
            'title' => 'My Orders',
        ],
        'payouts' => [
            'title'           => 'Payouts',
            'request-btn'     => 'Request Payout',
            'available-balance' => 'Available Balance',
            'request-success' => 'Payout request submitted successfully.',
        ],
    ],
];
