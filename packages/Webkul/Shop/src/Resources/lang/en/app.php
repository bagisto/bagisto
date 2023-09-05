<?php

return [
    'configurations' => [
        'settings-title'      => 'Settings',
        'settings-title-info' => 'Settings refer to configurable choices that control how a system, application, or device behaves, tailored to user preferences and requirements.',
    ],

    'customers' => [
        'forgot-password' => [
            'title'                => 'Recover Password',
            'email'                => 'Email',
            'forgot-password-text' => 'If you forgot your password, recover it by entering your email address.',
            'submit'               => 'Reset Password',
            'page-title'           => 'Forgot your password ?',
            'back'                 => 'Back to sign In ?',
            'sign-in-button'       => 'Sign In',
            'footer'               => '© Copyright 2010 - 2022, Webkul Software (Registered in India). All rights reserved.',
        ],

        'reset-password' => [
            'title'            => 'Reset Password',
            'email'            => 'Registered Email',
            'password'         => 'Password',
            'confirm-password' => 'Confirm Password',
            'back-link-title'  => 'Back to Sign In',
            'submit-btn-title' => 'Reset Password',
            'footer'           => '© Copyright 2010 - 2022, Webkul Software (Registered in India). All rights reserved.',
        ],

        'login-form' => [
            'page-title'          => 'Customer Login',
            'form-login-text'     => 'If you have an account, sign in with your email address.',
            'show-password'       => 'Show Password',
            'title'               => 'Sign In',
            'email'               => 'Email',
            'password'            => 'Password',
            'forgot-pass'         => 'Forgot Password?',
            'button-title'        => 'Sign In',
            'new-customer'        => 'New customer?',
            'create-your-account' => 'Create your account',
            'footer'              => '© Copyright 2010 - 2022, Webkul Software (Registered in India). All rights reserved.',
            'invalid-credentials' => 'Please check your credentials and try again.',
            'not-activated'       => 'Your activation seeks admin approval',
            'verify-first'        => 'Verify your email account first.',
        ],

        'signup-form' => [
            'page-title'                  => 'Become User',
            'form-signup-text'            => 'If you are new to our store, we glad to have you as member.',
            'sign-in-button'              => 'Sign In',
            'first-name'                  => 'First Name',
            'last-name'                   => 'Last Name',
            'email'                       => 'Email',
            'password'                    => 'Password',
            'confirm-pass'                => 'Confirm Password',
            'subscribe-to-newsletter'     => 'Subscribe to newsletter',
            'button-title'                => 'Register',
            'account-exists'              => 'Already have an account ?',
            'footer'                      => '© Copyright 2010 - 2022, Webkul Software (Registered in India). All rights reserved.',
            'success-verify'              => 'Account created successfully, an e-mail has been sent for verification.',
            'success-verify-email-unsent' => 'Account created successfully, but verification e-mail unsent.',
            'success'                     => 'Account created successfully.',
            'verified'                    => 'Your account has been verified, try to login now.',
            'verify-failed'               => 'We cannot verify your mail account.',
            'verification-not-sent'       => 'Error! Problem in sending verification email, please try again later.',
            'verification-sent'           => 'Verification email sent',
        ],

        'account' => [
            'home'      => 'Home',
            'profile'   => [
                'title'                   => 'Profile',
                'first-name'              => 'First Name',
                'last-name'               => 'Last Name',
                'gender'                  => 'Gender',
                'dob'                     => 'Date of Birth',
                'email'                   => 'Email',
                'delete-profile'          => 'Delete Profile',
                'edit-profile'            => 'Edit Profile',
                'edit'                    => 'Edit',
                'phone'                   => 'Phone',
                'current-password'        => 'Current Password',
                'new-password'            => 'New Password',
                'confirm-password'        => 'Confirm Password',
                'delete-success'          => 'Customer deleted successfully',
                'wrong-password'          => 'Wrong Password !',
                'delete-failed'           => 'Error encountered while deleting customer.',
                'order-pending'           => 'Cannot delete customer account because some Order(s) are pending or processing state.',
                'subscribe-to-newsletter' => 'Subscribe to newsletter',
                'delete'                  => 'Delete',
                'enter-password'          => 'Enter Your password',
                'male'                    => 'Male',
                'female'                  => 'Female',
                'other'                   => 'Other',
                'save'                    => 'Save',
            ],

            'addresses' => [
                'title'            => 'Address',
                'edit'             => 'Edit',
                'edit-address'     => 'Edit Address',
                'delete'           => 'Delete',
                'set-as-default'   => 'Set as Default',
                'add-address'      => 'Add Address',
                'company-name'     => 'Company Name',
                'vat-id'           => 'Vat ID',
                'address-1'        => 'Address 1',
                'address-2'        => 'Address 2',
                'city'             => 'City',
                'state'            => 'State',
                'select-country'   => 'Select Country',
                'country'          => 'Country',
                'default-address'  => 'Default Address',
                'first-name'       => 'First Name',
                'last-name'        => 'Last Name',
                'phone'            => 'Phone',
                'street-address'   => 'Street Address',
                'post-code'        => 'Post Code',
                'empty-address'    => 'You have not added an address to your account yet.',
                'create-success'   => 'Address have been successfully added.',
                'edit-success'     => 'Address updated successfully.',
                'default-delete'   => 'Default address cannot be changed.',
                'delete-success'   => 'Address successfully deleted',
                'save'             => 'Save',
                'security-warning' => 'Suspicious activity found!!!',
            ],

            'orders' => [
                'title'      => 'Orders',
                'order-id'   => 'Order ID',
                'order'      => 'Order',
                'order-date' => 'Order Date',
                'total'      => 'Total',

                'status'        => [
                    'title' => 'Status',

                    'options' => [
                        'processing'      => 'Processing',
                        'completed'       => 'Completed',
                        'canceled'        => 'Canceled',
                        'closed'          => 'Closed',
                        'pending'         => 'Pending',
                        'pending-payment' => 'Pending Payment',
                        'fraud'           => 'Fraud',
                    ],
                ],

                'action'      => 'Action',
                'empty-order' => 'You have not ordered any product yet',

                'view' => [
                    'title'              => 'View',
                    'page-title'         => 'Order #:order_id',
                    'total'              => 'Total',
                    'shipping-address'   => 'Shipping Address',
                    'billing-address'    => 'Billing Address',
                    'shipping-method'    => 'Shipping Method',
                    'payment-method'     => 'Payment Method',
                    'cancel-btn-title'   => 'Cancel',
                    'cancel-confirm-msg' => 'Are you sure you want to cancel this order ?',
                    'cancel-success'     => 'Your order has been canceled',
                    'cancel-error'       => 'Your order can not be canceled.',

                    'information' => [
                        'info'              => 'Information',
                        'placed-on'         => 'Placed On',
                        'sku'               => 'SKU',
                        'product-name'      => 'Name',
                        'price'             => 'Price',
                        'item-status'       => 'Item Status',
                        'subtotal'          => 'Subtotal',
                        'tax-percent'       => 'Tax Percent',
                        'tax-amount'        => 'Tax Amount',
                        'tax'               => 'Tax',
                        'grand-total'       => 'Grand Total',
                        'item-ordered'      => 'Ordered (:qty_ordered)',
                        'item-invoice'      => 'Invoiced (:qty_invoiced)',
                        'item-shipped'      => 'shipped (:qty_shipped)',
                        'item-canceled'     => 'Canceled (:qty_canceled)',
                        'item-refunded'     => 'Refunded (:qty_refunded)',
                        'shipping-handling' => 'Shipping & Handling',
                        'discount'          => 'Discount',
                        'total-paid'        => 'Total Paid',
                        'total-refunded'    => 'Total Refunded',
                        'total-due'         => 'Total Due',
                    ],

                    'invoices'  => [
                        'invoices'           => 'Invoices',
                        'individual-invoice' => 'Invoice #:invoice_id',
                        'sku'                => 'SKU',
                        'product-name'       => 'Name',
                        'price'              => 'Price',
                        'products-ordered'   => 'Products Ordered',
                        'qty'                => 'Qty',
                        'subtotal'           => 'Subtotal',
                        'tax-amount'         => 'Tax Amount',
                        'grand-total'        => 'Grand Total',
                        'shipping-handling'  => 'Shipping & Handling',
                        'discount'           => 'Discount',
                        'tax'                => 'Tax',
                        'print'              => 'Print',
                    ],

                    'shipments' => [
                        'shipments'           => 'Shipments',
                        'tracking-number'     => 'Tracking Number',
                        'individual-shipment' => 'Shipment #:shipment_id',
                        'sku'                 => 'SKU',
                        'product-name'        => 'Name',
                        'qty'                 => 'Qty',
                        'subtotal'            => 'Subtotal',
                    ],

                    'refunds'  => [
                        'refunds'           => 'Refunds',
                        'individual-refund' => 'Refund #:refund_id',
                        'sku'               => 'SKU',
                        'product-name'      => 'Name',
                        'price'             => 'Price',
                        'qty'               => 'Qty',
                        'tax-amount'        => 'Tax Amount',
                        'subtotal'          => 'Subtotal',
                        'grand-total'       => 'Grand Total',
                        'no-result-found'   => 'We could not find any records.',
                        'shipping-handling' => 'Shipping & Handling',
                        'discount'          => 'Discount',
                        'tax'               => 'Tax',
                        'adjustment-refund' => 'Adjustment Refund',
                        'adjustment-fee'    => 'Adjustment Fee',
                    ],
                ],
            ],

            'reviews'    => [
                'title'        => 'Reviews',
                'empty-review' => 'You have not reviewed any product yet',
            ],

            'downloadable-products'  => [
                'name'                => 'Downloadable Products',
                'orderId'             => 'Order Id',
                'title'               => 'Title',
                'date'                => 'Date',
                'status'              => 'Status',
                'remaining-downloads' => 'Remaining Downloads',
                'records-found'       => 'Record(s) found',
                'empty-product'       => 'You don’t have a product to download',
                'download-error'      => 'Download link has been expired.',
                'payment-error'       => 'Payment has not been done for this download.',
            ],

            'wishlist' => [
                'page-title'         => 'Wishlist',
                'title'              => 'Wishlist',
                'color'              => 'Color',
                'remove'             => 'Remove',
                'delete-all'         => 'Delete All',
                'empty'              => 'No products were added to the wishlist page.',
                'move-to-cart'       => 'Move To Cart',
                'profile'            => 'Profile',
                'removed'            => 'Item Successfully Removed From Wishlist',
                'remove-fail'        => 'Item Cannot Be Removed From Wishlist',
                'moved'              => 'Item successfully moved To cart',
                'product-removed'    => 'Product Is No More Available As Removed By Admin',
                'remove-all-success' => 'All the items from your wishlist have been removed',
                'see-details'        => 'See Details',
            ],
        ],
    ],

    'components' => [
        'layouts' => [
            'header' => [
                'title'         => 'Account',
                'welcome'       => 'Welcome',
                'welcome-guest' => 'Welcome Guest',
                'dropdown-text' => 'Manage Cart, Orders & Wishlist',
                'sign-in'       => 'Sign In',
                'sign-up'       => 'Sign Up',
                'account'       => 'Account',
                'cart'          => 'Cart',
                'profile'       => 'Profile',
                'wishlist'      => 'Wishlist',
                'compare'       => 'Compare',
                'orders'        => 'Orders',
                'cart'          => 'Cart',
                'logout'        => 'Logout',
                'search-text'   => 'Search products here',
                'search'        => 'Search',
            ],

            'footer' => [
                'newsletter-text'        => 'Get Ready for our Fun Newsletter!',
                'subscribe-stay-touch'   => 'Subscribe to stay in touch.',
                'subscribe-newsletter'   => 'Subscribe Newsletter',
                'subscribe'              => 'Subscribe',
                'footer-text'            => '© Copyright 2010 - 2023, Webkul Software (Registered in India). All rights reserved.',
                'locale'                 => 'Locale',
                'currency'               => 'Currency',
                'about-us'               => 'About Us',
                'customer-service'       => 'Customer Service',
                'whats-new'              => 'What’s New',
                'contact-us'             => 'Contact Us',
                'order-return'           => 'Order and Returns',
                'payment-policy'         => 'Payment Policy',
                'shipping-policy'        => 'Shipping Policy',
                'privacy-cookies-policy' => 'Privacy and Cookies Policy',
            ],
        ],

        'datagrid' => [
            'toolbar' => [
                'mass-actions' => [
                    'select-action' => 'Select Action',
                    'select-option' => 'Select Option',
                    'submit'        => 'Submit',
                ],

                'filter' => [
                    'title' => 'Filter',
                ],

                'search' => [
                    'title' => 'Search',
                ],
            ],

            'filters' => [
                'title' => 'Apply Filters',

                'custom-filters' => [
                    'title'     => 'Custom Filters',
                    'clear-all' => 'Clear All',
                ],

                'date-options' => [
                    'today'             => 'Today',
                    'yesterday'         => 'Yesterday',
                    'this-week'         => 'This Week',
                    'this-month'        => 'This Month',
                    'last-month'        => 'Last Month',
                    'last-three-months' => 'Last 3 Months',
                    'last-six-months'   => 'Last 6 Months',
                    'this-year'         => 'This Year',
                ],
            ],

            'table' => [
                'actions'              => 'Actions',
                'no-records-available' => 'No Records Available.',
            ],
        ],

        'products'   => [
            'card' => [
                'new'                => 'New',
                'sale'               => 'Sale',
                'review-description' => 'Be the first to review this product',
                'add-to-compare'     => 'Item added successfully to compare list.',
                'already-in-compare' => 'Item is already added to compare list.',
                'add-to-cart'        => 'Add To Cart',
            ],

            'carousel' => [
                'view-all' => 'View All',
            ],
        ],

        'range-slider' => [
            'range' => 'Range:',
        ],
    ],

    'products'  => [
        'reviews'                => 'Reviews',
        'add-to-cart'            => 'Add To Cart',
        'add-to-compare'         => 'Product added in compare.',
        'already-in-compare'     => 'Product is already added in compare.',
        'buy-now'                => 'Buy Now',
        'compare'                => 'Compare',
        'rating'                 => 'Rating',
        'title'                  => 'Title',
        'comment'                => 'Comment',
        'submit-review'          => 'Submit Review',
        'customer-review'        => 'Customer Reviews',
        'write-a-review'         => 'Write a Review',
        'stars'                  => 'Stars',
        'share'                  => 'Share',
        'empty-review'           => 'No Review found, be the first to review this product',
        'was-this-helpful'       => 'Was This Review Helpful?',
        'load-more'              => 'Load More',
        'add-image'              => 'Add Image',
        'description'            => 'Description',
        'additional-information' => 'Additional Information',
        'submit-success'         => 'Submit Successfully',
        'something-went-wrong'   => 'Something went wrong',
        'in-stock'               => 'In Stock',
        'available-for-order'    => 'Available For Order',
        'out-of-stock'           => 'Out of Stock',
        'related-product-title'  => 'Related Products',
        'up-sell-title'          => 'We found other products you might like!',
        'new'                    => 'New',
        'as-low-as'              => 'As low as',
        'starting-at'            => 'Starting at',
        'name'                   => 'Name',
        'qty'                    => 'Qty',
        'offers'                 => 'Buy :qty for :price each and save :discount%',

        'sort-by'                => [
            'title'   => 'Sort By',
            'options' => [
                'from-a-z'        => 'From A-Z',
                'from-z-a'        => 'From Z-A',
                'latest-first'    => 'Newest First',
                'oldest-first'    => 'Oldest First',
                'cheapest-first'  => 'Cheapest First',
                'expensive-first' => 'Expensive First',
            ],
        ],

        'view' => [
            'type' => [
                'configurable' => [
                    'select-options'       => 'Please select an option',
                    'select-above-options' => 'Please select above options',
                ],

                'bundle' => [
                    'none' => 'None',
                ],

                'downloadable' => [
                    'samples' => 'Samples',
                    'links'   => 'Links',
                    'sample'  => 'Sample',
                ],

                'grouped' => [
                    'name' => 'Name',
                ],
            ],

            'reviews' => [
                'cancel'      => 'Cancel',
                'success'     => 'Review submitted successfully.',
                'attachments' => 'Attachments',
            ],
        ],

        'configurations' => [
            'compare_options'  => 'Compare options',
            'wishlist-options' => 'Wishlist options',
        ],
    ],

    'categories' => [
        'filters' => [
            'filters'   => 'Filters:',
            'filter'    => 'Filter',
            'sort'      => 'Sort',
            'clear-all' => 'Clear All',
        ],

        'toolbar' => [
            'show' => 'Show',
        ],

        'view' => [
            'empty'     => 'No products available in this category',
            'load-more' => 'Load More',
        ],
    ],

    'search' => [
        'title'          => 'Search results for : :query',
        'configurations' => [
            'image-search-option' => 'Image Search Option',
        ],
    ],

    'compare'  => [
        'product-compare'    => 'Product Compare',
        'delete-all'         => 'Delete All',
        'empty-text'         => 'You have no items in your compare list',
        'title'              => 'Product Compare',
        'already-added'      => 'Item is already added to compare list',
        'item-add-success'   => 'Item added successfully to compare list',
        'remove-success'     => 'Item removed successfully.',
        'remove-all-success' => 'All items removed successfully.',
        'remove-error'       => 'Something went wrong, please try again later.',
    ],

    'checkout' => [
        'success' => [
            'title'         => 'Order successfully placed',
            'thanks'        => 'Thank you for your order!',
            'order-id-info' => 'Your order id is #:order_id',
            'info'          => 'We will email you, your order details and tracking information',
        ],

        'cart' => [
            'item-add-to-cart'          => 'Item Added Successfully',
            'return-to-shop'            => 'Return To Shop',
            'continue-to-checkout'      => 'Continue to Checkout',
            'rule-applied'              => 'Cart rule applied',
            'minimum-order-message'     => 'Minimum order amount is :amount',
            'suspended-account-message' => 'Your account has been suspended.',
            'missing-fields'            => 'Some required fields missing for this product.',
            'missing-options'           => 'Options are missing for this product.',
            'missing-links'             => 'Downloadable links are missing for this product.',
            'select-hourly-duration'    => 'Select a slot duration of one hour.',
            'qty-missing'               => 'At least one product should have more than 1 quantity.',
            'success-remove'            => 'Item is successfully removed from the cart.',
            'inventory-warning'         => 'The requested quantity is not available, please try again later.',
            'illegal'                   => 'Quantity cannot be lesser than one.',
            'inactive'                  => 'The item has been deactivated and subsequently removed from the cart.',

            'index' => [
                'home'                     => 'Home',
                'cart'                     => 'Cart',
                'view-cart'                => 'View Cart',
                'product-name'             => 'Product Name',
                'remove'                   => 'Remove',
                'quantity'                 => 'Quantity',
                'price'                    => 'Price',
                'tax'                      => 'Tax',
                'total'                    => 'Total',
                'continue-shopping'        => 'Continue Shopping',
                'update-cart'              => 'Update Cart',
                'move-to-wishlist-success' => 'Selected items successfully moved to wishlist.',
                'remove-selected-success'  => 'Selected items successfully removed from cart.',
                'empty-product'            => 'You don’t have a product in your cart.',
                'quantity-update'          => 'Quantity updated successfully',
                'see-details'              => 'See Details',
                'move-to-wishlist'         => 'Move To Wishlist',
            ],

            'coupon'   => [
                'code'            => 'Coupon code',
                'applied'         => 'Coupon applied',
                'apply'           => 'Apply Coupon',
                'error'           => 'Something went wrong',
                'remove'          => 'Remove Coupon',
                'invalid'         => 'Coupon code is invalid.',
                'discount'        => 'Coupon Discount',
                'apply-issue'     => 'Coupon code can\'t be applied.',
                'success-apply'   => 'Coupon code applied successfully.',
                'already-applied' => 'Coupon code already applied.',
                'enter-your-code' => 'Enter your code',
                'subtotal'        => 'Subtotal',
                'button-title'    => 'Apply',
            ],

            'mini-cart' => [
                'see-details'          => 'See Details',
                'shopping-cart'        => 'Shopping Cart',
                'offer-on-orders'      => 'Get Up To 30% OFF on your 1st order',
                'remove'               => 'Remove',
                'empty-cart'           => 'Your cart is empty',
                'subtotal'             => 'Subtotal',
                'continue-to-checkout' => 'Continue to Checkout',
                'view-cart'            => 'View Cart',
            ],

            'summary' => [
                'cart-summary'        => 'Cart Summary',
                'sub-total'           => 'Subtotal',
                'tax'                 => 'Tax',
                'delivery-charges'    => 'Delivery Charges',
                'discount-amount'     => 'Discount Amount',
                'grand-total'         => 'Grand Total',
                'place-order'         => 'Place Order',
                'proceed-to-checkout' => 'Proceed To Checkout',
            ],
        ],

        'onepage' => [
            'addresses' => [
                'billing' => [
                    'billing-address'      => 'Billing Address',
                    'add-new-address'      => 'Add new address',
                    'same-billing-address' => 'Address is the same as my billing address',
                    'back'                 => 'Back',
                    'company-name'         => 'Company Name',
                    'first-name'           => 'First Name',
                    'last-name'            => 'Last Name',
                    'email'                => 'Email',
                    'street-address'       => 'Street Address',
                    'country'              => 'Country',
                    'state'                => 'State',
                    'select-state'         => 'Select State',
                    'city'                 => 'City',
                    'postcode'             => 'Zip/Postcode',
                    'telephone'            => 'Telephone',
                    'save-address'         => 'Save this address',
                    'confirm'              => 'Confirm',
                ],

                'index' => [
                    'confirm' => 'Confirm',
                ],

                'shipping' => [
                    'shipping-address' => 'Shipping Address',
                    'add-new-address'  => 'Add new address',
                    'back'             => 'Back',
                    'company-name'     => 'Company Name',
                    'first-name'       => 'First Name',
                    'last-name'        => 'Last Name',
                    'email'            => 'Email',
                    'street-address'   => 'Street address',
                    'country'          => 'Country',
                    'state'            => 'State',
                    'select-state'     => 'Select State',
                    'select-country'   => 'Select Country',
                    'city'             => 'City',
                    'postcode'         => 'Zip/Postcode',
                    'telephone'        => 'Telephone',
                    'save-address'     => 'Save this address',
                    'confirm'          => 'Confirm',
                ],
            ],

            'coupon' => [
                'discount'        => 'Coupon Discount',
                'code'            => 'Coupon Code',
                'applied'         => 'Coupon Applied',
                'applied-coupon'  => 'Applied Coupon',
                'apply'           => 'Apply Coupon',
                'remove'          => 'Remove Coupon',
                'apply-issue'     => 'Coupon code can\'t be applied.',
                'sub-total'       => 'Subtotal',
                'button-title'    => 'Apply',
                'enter-your-code' => 'Enter your code',
                'subtotal'        => 'Subtotal',
            ],

            'index' => [
                'home'     => 'Home',
                'checkout' => 'Checkout',
            ],

            'payment' => [
                'payment-method' => 'Payment Method',
            ],

            'shipping' => [
                'shipping-method' => 'Shipping Method',
            ],

            'summary' => [
                'cart-summary'     => 'Cart Summary',
                'sub-total'        => 'Subtotal',
                'tax'              => 'Tax',
                'delivery-charges' => 'Delivery Charges',
                'discount-amount'  => 'Discount Amount',
                'grand-total'      => 'Grand Total',
                'place-order'      => 'Place Order',
            ],
        ],
    ],

    'home' => [
        'index' => [
            'offer'               => 'Get UPTO 40% OFF on your 1st order SHOP NOW',
            'verify-email'        => 'Verify your email account',
            'resend-verify-email' => 'Resend Verification Email',
        ],
    ],

    'errors' => [
        'go-to-home'   => 'Go To Home',

        '404' => [
            'title'       => '404 Page Not Found',
            'description' => 'Oops! The page you\'re looking for is on vacation. It seems we couldn\'t find what you were searching for.',
        ],

        '401' => [
            'title'       => '401 Unauthorized',
            'description' => 'Oops! Looks like you\'re not allowed to access this page. It seems you\'re missing the necessary credentials.',
        ],

        '403' => [
            'title'       => '403 Forbidden',
            'description' => 'Oops! This page is off-limits. It appears you don\'t have the required permissions to view this content.',
        ],

        '500' => [
            'title'       => '500 Internal Server Error',
            'description' => 'Oops! Something went wrong. It seems we\'re having trouble loading the page you\'re looking for.',
        ],

        '503' => [
            'title'       => '503 Service Unavailable',
            'description' => 'Oops! Looks like we\'re temporarily down for maintenance. Please check back in a bit.',
        ],
    ],

    'layouts' => [
        'my-account'            => 'My Account',
        'profile'               => 'Profile',
        'address'               => 'Address',
        'reviews'               => 'Reviews',
        'wishlist'              => 'Wishlist',
        'orders'                => 'Orders',
        'downloadable-products' => 'Downloadable Products',
    ],

    'subscription' => [
        'already'             => 'You are already subscribed to our newsletter.',
        'subscribe-success'   => 'You have successfully subscribed to our newsletter.',
        'unsubscribe-success' => 'You have successfully unsubscribed to our newsletter.',
    ],

    'emails' => [
        'dear'   => 'Dear :customer_name',
        'thanks' => 'If you need any kind of help please contact us at <a href=":link" style=":style">:email</a>.<br/>Thanks!',

        'customers' => [
            'registration' => [
                'subject'     => 'New Customer Registration',
                'greeting'    => 'Welcome and thank you for registering with us!',
                'description' => 'Your account has now been created successfully and you can login using your email address and password credentials. Upon logging in, you will be able to access other services including reviewing past orders, wishlists and editing your account information.',
                'sign-in'     => 'Sign in',
            ],

            'forgot-password' => [
                'subject'        => 'Reset Password Email',
                'greeting'       => 'Forgot Password!',
                'description'    => 'You are receiving this email because we received a password reset request for your account.',
                'reset-password' => 'Reset Password',
            ],

            'update-password' => [
                'subject'     => 'Password Updated',
                'greeting'    => 'Password Updated!',
                'description' => 'You are receiving this email because you have updated your password.',
            ],

            'verification' => [
                'subject'      => 'Account Verification Email',
                'greeting'     => 'Welcome!',
                'description'  => 'Please click the button below to verify your email address.',
                'verify-email' => 'Verify Email Address',
            ],

            'commented' => [
                'subject'     => 'New comment Added',
                'description' => 'Note Is - :note',
            ],

            'subscribed' => [
                'subject'     => 'You! Subscribe to Our Newsletter',
                'greeting'    => 'Welcome to our newsletter!',
                'description' => 'Congratulations and welcome to our newsletter community! We\'re excited to have you on board and keep you updated with the latest news, trends, and exclusive offers.',
                'unsubscribe' => 'Unsubscribe',
            ],
        ],

        'orders' => [
            'created' => [
                'subject'  => 'New Order Confirmation',
                'title'    => 'Order Confirmation!',
                'greeting' => 'Thanks for your Order :order_id placed on :created_at',
                'summary'  => 'Summary of Order',
            ],

            'invoiced' => [
                'subject'  => 'New Invoice Confirmation',
                'title'    => 'Invoice Confirmation!',
                'greeting' => 'Your invoice #:invoice_id for Order :order_id created on :created_at',
                'summary'  => 'Summary of Invoice',
            ],

            'shipped' => [
                'subject'  => 'New Shipment Confirmation',
                'title'    => 'Order Shipped!',
                'greeting' => 'Your order :order_id placed on :created_at has been shipped',
                'summary'  => 'Summary of Shipment',
            ],

            'refunded' => [
                'subject'  => 'New Refund Confirmation',
                'title'    => 'Order Refunded!',
                'greeting' => 'Refund has been initiated for the :order_id placed on :created_at',
                'summary'  => 'Summary of Refund',
            ],

            'canceled' => [
                'subject'  => 'New Order Canceled',
                'title'    => 'Order Canceled!',
                'greeting' => 'Your Order :order_id placed on :created_at has been canceled',
                'summary'  => 'Summary of Order',
            ],

            'commented' => [
                'subject' => 'New comment Added',
                'title'   => 'New comment added to your order :order_id placed on :created_at',
            ],

            'shipping-address'  => 'Shipping Address',
            'carrier'           => 'Carrier',
            'tracking-number'   => 'Tracking Number : :tracking_number',
            'billing-address'   => 'Billing Address',
            'contact'           => 'Contact',
            'shipping'          => 'Shipping',
            'payment'           => 'Payment',
            'sku'               => 'SKU',
            'name'              => 'Name',
            'price'             => 'Price',
            'qty'               => 'Qty',
            'subtotal'          => 'Subtotal',
            'shipping-handling' => 'Shipping Handling',
            'tax'               => 'Tax',
            'discount'          => 'Discount',
            'grand-total'       => 'Grand Total',
        ],
    ],
];
