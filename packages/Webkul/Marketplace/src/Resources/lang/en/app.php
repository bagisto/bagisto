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
            'transfer-failed' => 'Stripe transfer failed:',
        ],
        'subscriptions' => [
            'index' => [
                'title'   => 'Subscription Plans',
                'add-btn' => 'Add Plan',
            ],
            'create' => [
                'title' => 'Create Subscription Plan',
            ],
            'edit' => [
                'title' => 'Edit Subscription Plan',
            ],
            'create-success' => 'Subscription plan created successfully.',
            'update-success' => 'Subscription plan updated successfully.',
            'delete-success' => 'Subscription plan deleted.',
        ],
    ],
    'seller' => [
        'auth' => [
            'register'           => 'Become a Seller',
            'login-required'     => 'You need to log in before registering as a seller.',
            'already-registered' => 'You already have a seller account.',
            'register-success'   => 'Your seller account has been submitted for review.',
        ],
        'dashboard' => [
            'title'            => 'Seller Dashboard',
            'register-first'   => 'Please complete your seller registration first.',
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
            'title'             => 'Payouts',
            'request-btn'       => 'Request Payout',
            'available-balance' => 'Available Balance',
            'request-success'   => 'Payout request submitted successfully.',
        ],
        'connect' => [
            'title'                => 'Stripe Connect',
            'subtitle'             => 'Connect your Stripe account to receive automatic payouts.',
            'status'               => 'Connection status',
            'connected'            => 'Connected',
            'incomplete'           => 'Onboarding incomplete',
            'not-connected'        => 'Not connected',
            'charges'              => 'Charges enabled',
            'payouts'              => 'Payouts enabled',
            'connect-btn'          => 'Connect with Stripe',
            'continue-onboarding'  => 'Continue onboarding',
            'ready'                => 'Your account is ready to receive automatic payouts.',
            'success'              => 'Your Stripe account is connected and ready!',
            'pending'              => 'Stripe is still verifying your details. Please check back soon.',
            'not-configured'       => 'Stripe Connect is not configured yet. Add your Stripe secret key in Admin → Configure → Stripe, or set STRIPE_CONNECT_SECRET in .env.',
            'error'                => 'Could not start Stripe onboarding:',
        ],
        'subscriptions' => [
            'title'          => 'Subscription Plans',
            'subtitle'       => 'Choose a plan to sell on the marketplace.',
            'current-plan'   => 'Current plan',
            'renews'         => 'Renews on',
            'free'           => 'Free',
            'commission'     => 'commission',
            'products'       => 'products',
            'unlimited'      => 'Unlimited',
            'featured'       => 'Featured listings',
            'analytics'      => 'Analytics access',
            'current'        => 'Current plan',
            'choose-free'    => 'Choose Free',
            'subscribe'      => 'Subscribe',
            'cancel'         => 'Cancel plan',
            'cancel-confirm' => 'Are you sure you want to cancel your subscription?',
            'subscribed'     => 'Subscription activated successfully.',
            'cancelled'      => 'Your subscription has been cancelled.',
            'error'          => 'Could not start the subscription:',
            'stripe-note'    => 'Paid plans require Stripe to be configured. Free plans activate instantly.',
        ],
    ],
];
