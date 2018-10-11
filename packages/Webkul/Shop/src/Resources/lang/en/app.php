<?php

return [
    'home' => [
        'featured-products' => 'Featured Products',
        'new-products' => 'New Products'
    ],
    'customer' => [
        'signup-text' => [
            'account_exists' => 'Already have an account',
            'title' => 'Sign In'
        ],

        'signup-form' => [
            'page-title' => 'Customer - Registration Form',
            'title' => 'Sign Up',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'confirm_pass' => 'Confirm Password',
            'button_title' => 'Register',
            'agree' => 'Agree',
            'terms' => 'Terms',
            'conditions' => 'Conditions',
            'using' => 'by using this website'
        ],

        'login-text' => [
            'no_account' => 'Don\'t have account',
            'title' => 'Sign In',
        ],

        'login-form' => [
            'page-title' => 'Customer - Login',
            'title' => 'Sign In',
            'email' => 'E-Mail',
            'password' => 'Password',
            'forgot_pass' => 'Forgot Password?',
            'button_title' => 'Sign In',
            'remember' => 'Remember Me',
            'footer' => '© Copyright 2018 Webkul Software, All rights reserved.'
        ],

        'forgot-password' => [
            'title' => 'Recover Password',
            'email' => 'E-Mail',
            'submit' => 'Submit',
            'page_title' => 'Customer - Forgot Password Form'
        ],

        'account' => [
            'dashboard' => 'Customer - Edit Profile',

            'profile' => [
                'index' => [
                    'page-title' => 'Customer - Profile',
                    'title' => 'Profile',
                    'edit' => 'Edit',
                ],

                'fname' => 'First Name',
                'lname' => 'Last Name',
                'gender' => 'Gender',
                'dob' => 'Date Of Birth',
                'phone' => 'Phone',
                'email' => 'E-Mail',
                'opassword' => 'Old Password',
                'password' => 'Password',
                'cpassword' => 'Confirm Password',
                'submit' => 'Update Profile',

                'edit-profile' => [
                    'title' => 'Edit Profile',
                    'page-title' => 'Customer - Edit Profile Form'
                ]
            ],

            'address' => [
                'index' => [
                    'page-title' => 'Customer - Address',
                    'title' => 'Address',
                    'edit' => 'Edit',
                    'empty' => 'You don\'t have any saved addresses here, please create a new one by clicking the link below.',
                    'create' => 'Create Address',
                ],

                'edit' => [

                ]
            ]
        ]
    ],

    'products' => [
        'layered-nav-title' => 'Shop By',
        'price-label' => 'As low as',
        'remove-filter-link-title' => 'Clear All',
        'sort-by' => 'Sort By',
        'from-a-z' => 'From A-Z',
        'from-z-a' => 'From Z-A',
        'newest-first' => 'Newest First',
        'oldest-first' => 'Oldest First',
        'cheapest-first' => 'Cheapest First',
        'expansive-first' => 'Expansive First',
        'show' => 'Show',
        'pager-info' => 'Showing :showing of :total Items',
        'description' => 'Description',
        'specification' => 'Specification',
        'total-reviews' => ':total Reviews',
        'by' => 'By :name',
        'up-sell-title' => 'We found other products you might like!',
        'reviews-title' => 'Ratings & Reviews',
        'write-review-btn' => 'Write Review',
        'choose-option' => 'Choose an option'
    ],

    'wishlist' => [
        'title' => 'Wishlist',
        'deleteall' => 'Delete All',
        'moveall' => 'Move All Products To Cart'
    ],

    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' =>'Cart System Integrity Violation, Some Required Fields Missing',
                'missing_options' =>'Cart System Integrity Violation, Configurable product\'s options are missing',

            ],
            'title' => 'Shopping Cart',
            'empty' => 'Shopping Cart Is Empty',
            'continue-shopping' => 'Continue Shopping',
            'proceed-to-checkout' => 'Proceed To Checkout',
            'remove' => 'Remove',
            'remove-link' => 'Remove',
            'move-to-wishlist' => 'Move to Wishlist',
            'quantity' => [
                'quantity' => 'Quantity',
                'success' => 'Quantity successfully updated',
                'illegal' => 'Quantity cannot be lesser than one',
                'inventory_warning' => 'The requested quantity is not available, please try again later'
            ],
            'item' => [
                'error_remove' => 'No items to remove from the cart',
                'success' => 'Item successfully added to cart',
                'success_remove' => 'Item removed successfully',
                'error_add' => 'Item cannot be added to cart',
            ],
            'quantity-error' => 'Requested quantity is not available.'
        ],

        'onepage' => [
            'title' => 'Checkout',
            'information' => 'Information',
            'shipping' => 'Shipping',
            'payment' => 'Payment',
            'complete' => 'Complete',
            'billing-address' => 'Billing Address',
            'sign-in' => 'Sign In',
            'first-name' => 'First Name',
            'last-name' => 'Last Name',
            'email' => 'Email',
            'address1' => 'Address',
            'address2' => 'Address 2',
            'city' => 'City',
            'state' => 'State',
            'postcode' => 'Zip/Postcode',
            'phone' => 'Telephone',
            'country' => 'Country',
            'order-summary' => 'Order Summary',
            'shipping-address' => 'Shipping Address',
            'use_for_shipping' => 'Ship to this address',
            'continue' => 'Continue',
            'shipping-method' => 'Shipping Method',
            'payment-information' => 'Payment Information',
            'summary' => 'Summary of Order',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'billing-address' => 'Billing Address',
            'shipping-address' => 'Shipping Address',
            'contact' => 'Contact',
            'place-order' => 'Place Order'
        ],

        'total' => [
            'order-summary' => 'Order Summary',
            'sub-total' => 'Items',
            'grand-total' => 'Grand Total',
            'delivery-charges' => 'Delivery Charges',
            'tax' => 'Tax',
            'price' => 'price'
        ],

        'success' => [
            'title' => 'Order successfully placed',
            'thanks' => 'Thank you for your order!',
            'order-id-info' => 'Your order id is #:order_id',
            'info' => 'We will email you, your order details and tracking information.'
        ]
    ]
];