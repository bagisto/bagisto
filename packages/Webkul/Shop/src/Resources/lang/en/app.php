<?php

return [
    'home' => [
        'page-title' => 'Bagisto - Home',
        'featured-products' => 'Featured Products',
        'new-products' => 'New Products'
    ],

    'header' => [
        'title' => 'Account',
        'dropdown-text' => 'Manage Cart, Orders & Wishlist.',
        'sign-in' => 'Sign In',
        'sign-up' => 'Sign Up',
        'profile' => 'Profile',
        'wishlist' => 'Wishlist',
        'cart' => 'Cart',
        'logout' => 'Logout',
        'search-text' => 'Search products here...'
    ],

    'footer' => [
        'subscribe-newsletter' => 'Subscribe Newsletter',
        'subscribe' => 'Subscribe',
        'locale' => 'Locale',
        'currency' => 'Currency',
    ],

    'search' => [
        'no-results' => 'No Results Found.',
        'page-title' => 'Bagisto - Search',
        'found-results' => 'Search Results Found',
        'found-result' => 'Search Result Found'
    ],

    'reviews' => [
        'title' => 'Title',
        'add-review-page-title' => 'Add Review',
        'write-review' => 'Write a review',
        'review-title' => 'Give Your Review a Title',
        'product-review-page-title' => 'Product Review',
        'rating-reviews' => 'Rating & Reviews',
        'submit' => 'SUBMIT',
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
            'title' => 'Sign Up',
        ],

        'login-form' => [
            'page-title' => 'Customer - Login',
            'title' => 'Sign In',
            'email' => 'Email',
            'password' => 'Password',
            'forgot_pass' => 'Forgot Password?',
            'button_title' => 'Sign In',
            'remember' => 'Remember Me',
            'footer' => '© Copyright 2018 Webkul Software, All rights reserved.'
        ],

        'forgot-password' => [
            'title' => 'Recover Password',
            'email' => 'Email',
            'submit' => 'Submit',
            'page_title' => 'Customer - Forgot Password Form'
        ],

        'reset-password' => [
            'title' => 'Reset Password',
            'email' => 'Registered Email',
            'password' => 'Password',
            'confirm-password' => 'Confirm Password',
            'back-link-title' => 'Back to Sign In',
            'submit-btn-title' => 'Reset Password'
        ],

        'account' => [
            'dashboard' => 'Customer - Edit Profile',
            'menu' => 'Menu',

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
                'email' => 'Email',
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
                    'add' => 'Add Address',
                    'edit' => 'Edit',
                    'empty' => 'You don\'t have any saved addresses here, please create a new one by clicking the link below.',
                    'create' => 'Create Address',
                    'delete' => 'Delete',
                    'make-default' => 'Make Default',
                    'default' => 'Default'
                ],

                'create' => [
                    'page-title' => 'Customer - Add Address Form',
                    'title' => 'Add Address',
                    'address1' => 'Address Line 1',
                    'address2' => 'Address Line 2',
                    'country' => 'Country',
                    'state' => 'State',
                    'city' => 'City',
                    'postcode' => 'Postal Code',
                    'submit' => 'Create Address'
                ],

                'edit' => [
                    'page-title' => 'Customer - Edit Address',
                    'title' => 'Edit Address',
                    'submit' => 'Edit Address'
                ],
                'delete' => [
                    'success' => 'Address Successfully Deleted',
                    'failure' => 'Address Cannot Be Deleted'
                ]
            ],

            'order' => [
                'index' => [
                    'page-title' => 'Customer - Orders',
                    'title' => 'Orders',
                    'order_id' => 'Order ID',
                    'date' => 'Date',
                    'status' => 'Status',
                    'total' => 'Total'
                ],

                'view' => [
                    'page-tile' => 'Order #:order_id',
                    'info' => 'Information',
                    'placed-on' => 'Placed On',
                    'products-ordered' => 'Products Ordered',
                    'invoices' => 'Invoices',
                    'shipments' => 'Shipments',
                    'SKU' => 'SKU',
                    'product-name' => 'Name',
                    'qty' => 'Qty',
                    'item-status' => 'Item Status',
                    'item-ordered' => 'Ordered (:qty_ordered)',
                    'item-invoice' => 'Invoiced (:qty_invoiced)',
                    'item-shipped' => 'shipped (:qty_shipped)',
                    'item-canceled' => 'Canceled (:qty_canceled)',
                    'price' => 'Price',
                    'total' => 'Total',
                    'subtotal' => 'Subtotal',
                    'shipping-handling' => 'Shipping & Handling',
                    'tax' => 'Tax',
                    'tax-percent' => 'Tax Percent',
                    'tax-amount' => 'Tax Amount',
                    'discount-amount' => 'Discount Amount',
                    'grand-total' => 'Grand Total',
                    'total-paid' => 'Total Paid',
                    'total-refunded' => 'Total Refunded',
                    'total-due' => 'Total Due',
                    'shipping-address' => 'Shipping Address',
                    'billing-address' => 'Billing Address',
                    'shipping-method' => 'Shipping Method',
                    'payment-method' => 'Payment Method',
                    'individual-invoice' => 'Invoice #:invoice_id',
                    'individual-shipment' => 'Shipment #:shipment_id',
                ]
            ],

            'review' => [
                'index' => [
                    'title' => 'Reviews',
                    'page-title' => 'Customer - Reviews'
                ],

                'view' => [
                    'page-tile' => 'Review #:id',
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
        'choose-option' => 'Choose an option',
        'sale' => 'Sale',
        'new' => 'New',
        'empty' => 'No products available in this category.',
        'add-to-cart' => 'Add To Cart',
        'buy-now' => 'Buy Now',
        'whoops' => 'Whoops!',
        'quantity' => 'Quantity',
        'in-stock' => 'In Stock',
        'out-of-stock' => 'Out Of Stock'
    ],

    'wishlist' => [
        'title' => 'Wishlist',
        'deleteall' => 'Delete All',
        'moveall' => 'Move All Products To Cart',
        'move-to-cart' => 'Move To Cart',
        'empty' => 'You Have No Items In Your Wishlist',
        'add' => 'Item Successfully Added To Wishlist',
        'remove' => 'Item Successfully Removed From Wishlist'
    ],

    'buynow' => [
        'no-options' => 'Please Select Options Before Buying This Product'
    ],


    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' =>'Cart System Integrity Violation, Some Required Fields Missing',
                'missing_options' =>'Cart System Integrity Violation, Configurable product\'s options are missing',
            ],

            'title' => 'Shopping Cart',
            'empty' => 'Your shopping cart is empty.',
            'update-cart' => 'Update Cart',
            'continue-shopping' => 'Continue Shopping',
            'proceed-to-checkout' => 'Proceed To Checkout',
            'remove' => 'Remove',
            'remove-link' => 'Remove',
            'move-to-wishlist' => 'Move to Wishlist',
            'add-config-warning' => 'Please Select Option Before Adding To Cart',
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
            'quantity-error' => 'Requested quantity is not available.',
            'cart-subtotal' => 'Cart Subtotal'
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
            'select-state' => 'Select a region, state or province.',
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
    ],

    'mail' => [
        'order' => [
            'subject' => 'New Order Confirmation',
            'heading' => 'Order Confirmation!',
            'dear' => 'Dear :customer_name',
            'greeting' => 'Thanks for your Order :order_id placed on :created_at',
            'summary' => 'Summary of Order',
            'shipping-address' => 'Shipping Address',
            'billing-address' => 'Billing Address',
            'contact' => 'Contact',
            'shipping' => 'Shipping',
            'payment' => 'Payment',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'subtotal' => 'Subtotal',
            'shipping-handling' => 'Shipping & Handling',
            'tax' => 'Tax',
            'grand-total' => 'Grand Total',
            'final-summary' => 'Thanks for showing your intrest in our store. we will send you track number once it shiped.',
            'help' => 'If you need any kind of help please contact us at :support_email',
            'thanks' => 'Thanks!'
        ],
        'invoice' => [
            'heading' => 'Your Invoice #:invoice_id for Order #:order_id',
            'subject' => 'Invoice for your order #:order_id',
            'summary' => 'Summary of Invoice',
        ],
        'shipment' => [
            'heading' => 'Your Shipment #:shipment_id for Order #:order_id',
            'subject' => 'Shipment for your order #:order_id',
            'summary' => 'Summary of Shipment',
            'carrier' => 'Carrier',
            'tracking-number' => 'Tracking Number'
        ],
        'forget-password' => [
            'dear' => 'Dear :name',
            'info' => 'You are receiving this email because we received a password reset request for your account.',
            'reset-password' => 'Reset Password',
            'final-summary' => 'If you did not request a password reset, no further action is required.',
            'thanks' => 'Thanks!'
        ]
    ],

    'webkul' => [
        'copy-right' => '© Copyright 2018 Webkul Software, All rights reserved.'
    ]
];