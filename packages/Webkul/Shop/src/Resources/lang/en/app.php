<?php

return [
    'customers' => [
        'forgot-password' => [
            'back'                 => 'Back to sign In ?',
            'email-not-exist'      => 'We cannot find a user with that email address.',
            'email'                => 'Email',
            'forgot-password-text' => 'If you forgot your password, recover it by entering your email address.',
            'footer'               => '© Copyright 2010 - :current_year, Webkul Software (Registered in India). All rights reserved.',
            'page-title'           => 'Forgot your password ?',
            'reset-link-sent'      => 'We have e-mailed your reset password link.',
            'submit'               => 'Reset Password',
            'sign-in-button'       => 'Sign In',
            'title'                => 'Recover Password',
        ],

        'reset-password' => [
            'back-link-title'  => 'Back to Sign In',
            'confirm-password' => 'Confirm Password',
            'email'            => 'Registered Email',
            'footer'           => '© Copyright 2010 - :current_year, Webkul Software (Registered in India). All rights reserved.',
            'password'         => 'Password',
            'submit-btn-title' => 'Reset Password',
            'title'            => 'Reset Password',
        ],

        'login-form' => [
            'button-title'        => 'Sign In',
            'create-your-account' => 'Create your account',
            'email'               => 'Email',
            'form-login-text'     => 'If you have an account, sign in with your email address.',
            'footer'              => '© Copyright 2010 - :current_year, Webkul Software (Registered in India). All rights reserved.',
            'forgot-pass'         => 'Forgot Password?',
            'invalid-credentials' => 'Please check your credentials and try again.',
            'not-activated'       => 'Your activation seeks admin approval',
            'new-customer'        => 'New customer?',
            'page-title'          => 'Customer Login',
            'password'            => 'Password',
            'show-password'       => 'Show Password',
            'title'               => 'Sign In',
            'verify-first'        => 'Verify your email account first.',
        ],

        'signup-form' => [
            'account-exists'              => 'Already have an account ?',
            'button-title'                => 'Register',
            'confirm-pass'                => 'Confirm Password',
            'email'                       => 'Email',
            'first-name'                  => 'First Name',
            'footer'                      => '© Copyright 2010 - :current_year, Webkul Software (Registered in India). All rights reserved.',
            'form-signup-text'            => 'If you are new to our store, we glad to have you as member.',
            'last-name'                   => 'Last Name',
            'page-title'                  => 'Become User',
            'password'                    => 'Password',
            'sign-in-button'              => 'Sign In',
            'subscribe-to-newsletter'     => 'Subscribe to newsletter',
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
                'current-password'        => 'Current Password',
                'confirm-password'        => 'Confirm Password',
                'dob'                     => 'Date of Birth',
                'delete-profile'          => 'Delete Profile',
                'delete-success'          => 'Customer deleted successfully',
                'delete-failed'           => 'Error encountered while deleting customer.',
                'delete'                  => 'Delete',
                'email'                   => 'Email',
                'edit-profile'            => 'Edit Profile',
                'edit'                    => 'Edit',
                'edit-success'            => 'Profile Updated Successfully',
                'enter-password'          => 'Enter Your password',
                'first-name'              => 'First Name',
                'female'                  => 'Female',
                'gender'                  => 'Gender',
                'last-name'               => 'Last Name',
                'male'                    => 'Male',
                'new-password'            => 'New Password',
                'other'                   => 'Other',
                'order-pending'           => 'Cannot delete customer account because some Order(s) are pending or processing state.',
                'phone'                   => 'Phone',
                'save'                    => 'Save',
                'subscribe-to-newsletter' => 'Subscribe to newsletter',
                'title'                   => 'Profile',
                'unmatch'                 => 'The old password does not match.',
                'wrong-password'          => 'Wrong Password !',
            ],

            'addresses' => [
                'add-address'      => 'Add Address',
                'address-1'        => 'Address 1',
                'address-2'        => 'Address 2',
                'city'             => 'City',
                'country'          => 'Country',
                'create-success'   => 'Address have been successfully added.',
                'company-name'     => 'Company Name',
                'delete'           => 'Delete',
                'default-address'  => 'Default Address',
                'default-delete'   => 'Default address cannot be changed.',
                'delete-success'   => 'Address successfully deleted',
                'edit'             => 'Edit',
                'edit-address'     => 'Edit Address',
                'empty-address'    => 'You have not added an address to your account yet.',
                'edit-success'     => 'Address updated successfully.',
                'first-name'       => 'First Name',
                'last-name'        => 'Last Name',
                'phone'            => 'Phone',
                'post-code'        => 'Post Code',
                'state'            => 'State',
                'set-as-default'   => 'Set as Default',
                'select-country'   => 'Select Country',
                'street-address'   => 'Street Address',
                'save'             => 'Save',
                'security-warning' => 'Suspicious activity found!!!',
                'title'            => 'Address',
                'vat-id'           => 'Vat ID',
            ],

            'orders' => [
                'order-id'   => 'Order ID',
                'order'      => 'Order',
                'order-date' => 'Order Date',
                'title'      => 'Orders',
                'total'      => 'Total',

                'status'        => [
                    'title' => 'Status',

                    'options' => [
                        'completed'       => 'Completed',
                        'canceled'        => 'Canceled',
                        'closed'          => 'Closed',
                        'fraud'           => 'Fraud',
                        'processing'      => 'Processing',
                        'pending'         => 'Pending',
                        'pending-payment' => 'Pending Payment',
                    ],
                ],

                'action'      => 'Action',
                'empty-order' => 'You have not ordered any product yet',

                'view' => [
                    'billing-address'    => 'Billing Address',
                    'cancel-btn-title'   => 'Cancel',
                    'cancel-confirm-msg' => 'Are you sure you want to cancel this order ?',
                    'cancel-success'     => 'Your order has been canceled',
                    'cancel-error'       => 'Your order can not be canceled.',
                    'payment-method'     => 'Payment Method',
                    'page-title'         => 'Order #:order_id',
                    'shipping-address'   => 'Shipping Address',
                    'shipping-method'    => 'Shipping Method',
                    'total'              => 'Total',
                    'title'              => 'View',

                    'information' => [
                        'discount'          => 'Discount',
                        'grand-total'       => 'Grand Total',
                        'info'              => 'Information',
                        'item-status'       => 'Item Status',
                        'item-ordered'      => 'Ordered (:qty_ordered)',
                        'item-invoice'      => 'Invoiced (:qty_invoiced)',
                        'item-shipped'      => 'shipped (:qty_shipped)',
                        'item-canceled'     => 'Canceled (:qty_canceled)',
                        'item-refunded'     => 'Refunded (:qty_refunded)',
                        'placed-on'         => 'Placed On',
                        'product-name'      => 'Name',
                        'price'             => 'Price',
                        'sku'               => 'SKU',
                        'subtotal'          => 'Subtotal',
                        'shipping-handling' => 'Shipping & Handling',
                        'tax-percent'       => 'Tax Percent',
                        'tax-amount'        => 'Tax Amount',
                        'tax'               => 'Tax',
                        'total-paid'        => 'Total Paid',
                        'total-refunded'    => 'Total Refunded',
                        'total-due'         => 'Total Due',
                    ],

                    'invoices'  => [
                        'discount'           => 'Discount',
                        'grand-total'        => 'Grand Total',
                        'invoices'           => 'Invoices',
                        'individual-invoice' => 'Invoice #:invoice_id',
                        'product-name'       => 'Name',
                        'price'              => 'Price',
                        'products-ordered'   => 'Products Ordered',
                        'print'              => 'Print',
                        'qty'                => 'Qty',
                        'sku'                => 'SKU',
                        'subtotal'           => 'Subtotal',
                        'shipping-handling'  => 'Shipping & Handling',
                        'tax-amount'         => 'Tax Amount',
                        'tax'                => 'Tax',
                    ],

                    'shipments' => [
                        'individual-shipment' => 'Shipment #:shipment_id',
                        'product-name'        => 'Name',
                        'qty'                 => 'Qty',
                        'sku'                 => 'SKU',
                        'shipments'           => 'Shipments',
                        'subtotal'            => 'Subtotal',
                        'tracking-number'     => 'Tracking Number',
                    ],

                    'refunds'  => [
                        'adjustment-refund' => 'Adjustment Refund',
                        'adjustment-fee'    => 'Adjustment Fee',
                        'discount'          => 'Discount',
                        'grand-total'       => 'Grand Total',
                        'individual-refund' => 'Refund #:refund_id',
                        'no-result-found'   => 'We could not find any records.',
                        'product-name'      => 'Name',
                        'price'             => 'Price',
                        'qty'               => 'Qty',
                        'refunds'           => 'Refunds',
                        'sku'               => 'SKU',
                        'subtotal'          => 'Subtotal',
                        'shipping-handling' => 'Shipping & Handling',
                        'tax-amount'        => 'Tax Amount',
                        'tax'               => 'Tax',
                    ],
                ],

                'invoice-pdf' => [
                    'bank-details'      => 'Bank Details',
                    'bill-to'           => 'Bill to',
                    'contact'           => 'Contact',
                    'contact-number'    => 'Contact Number',
                    'date'              => 'Invoice Date',
                    'discount'          => 'Discount',
                    'grand-total'       => 'Grand Total',
                    'invoice'           => 'Invoice',
                    'invoice-id'        => 'Invoice ID',
                    'order-date'        => 'Order Date',
                    'order-id'          => 'Order ID',
                    'payment-method'    => 'Payment Method',
                    'payment-terms'     => 'Payment Terms',
                    'price'             => 'Price',
                    'product-name'      => 'Product Name',
                    'qty'               => 'Quantity',
                    'ship-to'           => 'Ship to',
                    'shipping-handling' => 'Shipping Handling',
                    'shipping-method'   => 'Shipping Method',
                    'sku'               => 'SKU',
                    'subtotal'          => 'Subtotal',
                    'tax'               => 'Tax',
                    'tax-amount'        => 'Tax Amount',
                    'vat-number'        => 'Vat Number',
                ],
            ],

            'reviews'    => [
                'empty-review' => 'You have not reviewed any product yet',
                'title'        => 'Reviews',
            ],

            'downloadable-products'  => [
                'date'                => 'Date',
                'download-error'      => 'Download link has been expired.',
                'empty-product'       => 'You don’t have a product to download',
                'name'                => 'Downloadable Products',
                'orderId'             => 'Order Id',
                'payment-error'       => 'Payment has not been done for this download.',
                'remaining-downloads' => 'Remaining Downloads',
                'records-found'       => 'Record(s) found',
                'status'              => 'Status',
                'title'               => 'Title',
            ],

            'wishlist' => [
                'color'              => 'Color',
                'delete-all'         => 'Delete All',
                'empty'              => 'No products were added to the wishlist page.',
                'move-to-cart'       => 'Move To Cart',
                'moved-success'      => 'Item Successfully Moved to Cart',
                'moved'              => 'Item successfully moved To cart',
                'page-title'         => 'Wishlist',
                'profile'            => 'Profile',
                'product-removed'    => 'Product Is No More Available As Removed By Admin',
                'remove'             => 'Remove',
                'removed'            => 'Item Successfully Removed From Wishlist',
                'remove-fail'        => 'Item Cannot Be Removed From Wishlist',
                'remove-all-success' => 'All the items from your wishlist have been removed',
                'success'            => 'Item Successfully Added To Wishlist',
                'see-details'        => 'See Details',
                'title'              => 'Wishlist',
            ],
        ],
    ],

    'components' => [
        'accordion' => [
            'default-content' => 'Default Content',
            'default-header'  => 'Default Header',
        ],

        'drawer' => [
            'default-toggle'  => 'Default Toggle',
        ],

        'media' => [
            'add-attachments' => 'Add attachments',
        ],

        'layouts' => [
            'header' => [
                'account'       => 'Account',
                'compare'       => 'Compare',
                'cart'          => 'Cart',
                'dropdown-text' => 'Manage Cart, Orders & Wishlist',
                'logout'        => 'Logout',
                'orders'        => 'Orders',
                'profile'       => 'Profile',
                'sign-in'       => 'Sign In',
                'sign-up'       => 'Sign Up',
                'search-text'   => 'Search products here',
                'search'        => 'Search',
                'title'         => 'Account',
                'welcome'       => 'Welcome',
                'welcome-guest' => 'Welcome Guest',
                'wishlist'      => 'Wishlist',
            ],

            'footer' => [
                'about-us'               => 'About Us',
                'customer-service'       => 'Customer Service',
                'contact-us'             => 'Contact Us',
                'currency'               => 'Currency',
                'footer-text'            => '© Copyright 2010 - 2023, Webkul Software (Registered in India). All rights reserved.',
                'locale'                 => 'Locale',
                'newsletter-text'        => 'Get Ready for our Fun Newsletter!',
                'order-return'           => 'Order and Returns',
                'payment-policy'         => 'Payment Policy',
                'privacy-cookies-policy' => 'Privacy and Cookies Policy',
                'subscribe-stay-touch'   => 'Subscribe to stay in touch.',
                'subscribe-newsletter'   => 'Subscribe Newsletter',
                'subscribe'              => 'Subscribe',
                'shipping-policy'        => 'Shipping Policy',
                'whats-new'              => 'What’s New',
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

                'dropdown' => [
                    'searchable' => [
                        'atleast-two-chars' => 'Type atleast 2 characters...',
                        'no-results'        => 'No result found...',
                    ],
                ],

                'custom-filters' => [
                    'clear-all' => 'Clear All',
                    'title'     => 'Custom Filters',
                ],

                'date-options' => [
                    'last-six-months'   => 'Last 6 Months',
                    'last-month'        => 'Last Month',
                    'last-three-months' => 'Last 3 Months',
                    'today'             => 'Today',
                    'this-week'         => 'This Week',
                    'this-month'        => 'This Month',
                    'this-year'         => 'This Year',
                    'yesterday'         => 'Yesterday',
                ],
            ],

            'table' => [
                'actions'              => 'Actions',
                'no-records-available' => 'No Records Available.',
            ],
        ],

        'modal' => [
            'default-content' => 'Default Content',
            'default-header'  => 'Default Header',

            'confirm' => [
                'agree-btn'    => 'Agree',
                'disagree-btn' => 'Disagree',
                'message'      => 'Are you sure you want to perform this action?',
                'title'        => 'Are you sure?',
            ],
        ],

        'products' => [
            'card' => [
                'add-to-compare'     => 'Item added successfully to compare list.',
                'already-in-compare' => 'Item is already added to compare list.',
                'add-to-cart'        => 'Add To Cart',
                'new'                => 'New',
                'review-description' => 'Be the first to review this product',
                'sale'               => 'Sale',
            ],

            'carousel' => [
                'view-all' => 'View All',
            ],
        ],

        'range-slider' => [
            'range' => 'Range:',
        ],
    ],

    'products' => [
        'prices' => [
            'grouped' => [
                'starting-at' => 'Starting at',
            ],

            'configurable' => [
                'as-low-as' => 'As low as',
            ],
        ],

        'sort-by' => [
            'title'   => 'Sort By',
            'options' => [
                'cheapest-first'  => 'Cheapest First',
                'expensive-first' => 'Expensive First',
                'from-a-z'        => 'From A-Z',
                'from-z-a'        => 'From Z-A',
                'latest-first'    => 'Newest First',
                'oldest-first'    => 'Oldest First',
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
                    'links'   => 'Links',
                    'sample'  => 'Sample',
                    'samples' => 'Samples',
                ],

                'grouped' => [
                    'name' => 'Name',
                ],
            ],

            'gallery' => [
                'product-image'   => 'Product Image',
                'thumbnail-image' => 'Thumbnail Image',
            ],

            'reviews' => [
                'attachments'      => 'Attachments',
                'comment'          => 'Comment',
                'customer-review'  => 'Customer Reviews',
                'cancel'           => 'Cancel',
                'empty-review'     => 'No Review found, be the first to review this product',
                'failed-to-upload' => 'The image failed to upload',
                'load-more'        => 'Load More',
                'rating'           => 'Rating',
                'success'          => 'Review submitted successfully.',
                'submit-review'    => 'Submit Review',
                'title'            => 'Title',
                'write-a-review'   => 'Write a Review',
                'name'             => 'Name',
            ],

            'add-to-cart'            => 'Add To Cart',
            'add-to-compare'         => 'Product added in compare.',
            'already-in-compare'     => 'Product is already added in compare.',
            'additional-information' => 'Additional Information',
            'buy-now'                => 'Buy Now',
            'compare'                => 'Compare',
            'description'            => 'Description',
            'review'                 => 'Reviews',
            'related-product-title'  => 'Related Products',
            'tax-inclusive'          => 'Inclusive of all taxes',
            'up-sell-title'          => 'We found other products you might like!',
        ],

        'type' => [
            'abstract' => [
                'offers' => 'Buy :qty for :price each and save :discount%',
            ],
        ],
    ],

    'categories' => [
        'filters' => [
            'clear-all' => 'Clear All',
            'filters'   => 'Filters:',
            'filter'    => 'Filter',
            'sort'      => 'Sort',
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
        'title' => 'Search results for : :query',

        'images' => [
            'index' => [
                'something-went-wrong' => 'Something went wrong, please try again later.',
                'size-limit-error'     => 'Size Limit Error',
                'only-images-allowed'  => 'Only images (.jpeg, .jpg, .png, ..) are allowed.',
            ],

            'results' => [
                'analysed-keywords' => 'Analysed Keywords:',
            ],
        ],
    ],

    'compare' => [
        'already-added'      => 'Item is already added to compare list',
        'delete-all'         => 'Delete All',
        'empty-text'         => 'You have no items in your compare list',
        'item-add-success'   => 'Item added successfully to compare list',
        'product-compare'    => 'Product Compare',
        'remove-success'     => 'Item removed successfully.',
        'remove-all-success' => 'All items removed successfully.',
        'remove-error'       => 'Something went wrong, please try again later.',
        'title'              => 'Product Compare',
    ],

    'checkout' => [
        'success' => [
            'info'          => 'We will email you, your order details and tracking information',
            'order-id-info' => 'Your order id is #:order_id',
            'title'         => 'Order successfully placed',
            'thanks'        => 'Thank you for your order!',
        ],

        'cart' => [
            'continue-to-checkout'      => 'Continue to Checkout',
            'item-add-to-cart'          => 'Item Added Successfully',
            'inventory-warning'         => 'The requested quantity is not available, please try again later.',
            'illegal'                   => 'Quantity cannot be lesser than one.',
            'inactive'                  => 'The item has been deactivated and subsequently removed from the cart.',
            'missing-fields'            => 'Some required fields missing for this product.',
            'missing-options'           => 'Options are missing for this product.',
            'missing-links'             => 'Downloadable links are missing for this product.',
            'minimum-order-message'     => 'Minimum order amount is :amount',
            'qty-missing'               => 'At least one product should have more than 1 quantity.',
            'return-to-shop'            => 'Return To Shop',
            'rule-applied'              => 'Cart rule applied',
            'suspended-account-message' => 'Your account has been suspended.',
            'select-hourly-duration'    => 'Select a slot duration of one hour.',
            'success-remove'            => 'Item is successfully removed from the cart.',

            'index' => [
                'cart'                     => 'Cart',
                'continue-shopping'        => 'Continue Shopping',
                'empty-product'            => 'You don’t have a product in your cart.',
                'home'                     => 'Home',
                'items-selected'           => ':count Items Selected',
                'move-to-wishlist-success' => 'Selected items successfully moved to wishlist.',
                'move-to-wishlist'         => 'Move To Wishlist',
                'product-name'             => 'Product Name',
                'price'                    => 'Price',
                'quantity'                 => 'Quantity',
                'quantity-update'          => 'Quantity updated successfully',
                'remove'                   => 'Remove',
                'remove-selected-success'  => 'Selected items successfully removed from cart.',
                'see-details'              => 'See Details',
                'tax'                      => 'Tax',
                'total'                    => 'Total',
                'update-cart'              => 'Update Cart',
                'view-cart'                => 'View Cart',
            ],

            'coupon'   => [
                'applied'         => 'Coupon applied',
                'apply'           => 'Apply Coupon',
                'apply-issue'     => 'Coupon code can\'t be applied.',
                'already-applied' => 'Coupon code already applied.',
                'button-title'    => 'Apply',
                'code'            => 'Coupon code',
                'discount'        => 'Coupon Discount',
                'error'           => 'Something went wrong',
                'enter-your-code' => 'Enter your code',
                'invalid'         => 'Coupon code is invalid.',
                'remove'          => 'Remove Coupon',
                'success-apply'   => 'Coupon code applied successfully.',
                'subtotal'        => 'Subtotal',
            ],

            'mini-cart' => [
                'continue-to-checkout' => 'Continue to Checkout',
                'empty-cart'           => 'Your cart is empty',
                'offer-on-orders'      => 'Get Up To 30% OFF on your 1st order',
                'remove'               => 'Remove',
                'see-details'          => 'See Details',
                'shopping-cart'        => 'Shopping Cart',
                'subtotal'             => 'Subtotal',
                'view-cart'            => 'View Cart',
            ],

            'summary' => [
                'cart-summary'        => 'Cart Summary',
                'delivery-charges'    => 'Delivery Charges',
                'discount-amount'     => 'Discount Amount',
                'grand-total'         => 'Grand Total',
                'place-order'         => 'Place Order',
                'proceed-to-checkout' => 'Proceed To Checkout',
                'sub-total'           => 'Subtotal',
                'tax'                 => 'Tax',
            ],
        ],

        'onepage' => [
            'addresses' => [
                'billing' => [
                    'add-new-address'      => 'Add new address',
                    'billing-address'      => 'Billing Address',
                    'back'                 => 'Back',
                    'company-name'         => 'Company Name',
                    'country'              => 'Country',
                    'city'                 => 'City',
                    'confirm'              => 'Confirm',
                    'email'                => 'Email',
                    'first-name'           => 'First Name',
                    'last-name'            => 'Last Name',
                    'postcode'             => 'Zip/Postcode',
                    'same-billing-address' => 'Address is the same as my billing address',
                    'street-address'       => 'Street Address',
                    'state'                => 'State',
                    'select-state'         => 'Select State',
                    'save-address'         => 'Save this address',
                    'telephone'            => 'Telephone',
                ],

                'index' => [
                    'confirm' => 'Confirm',
                ],

                'shipping' => [
                    'add-new-address'  => 'Add new address',
                    'back'             => 'Back',
                    'company-name'     => 'Company Name',
                    'country'          => 'Country',
                    'city'             => 'City',
                    'confirm'          => 'Confirm',
                    'email'            => 'Email',
                    'first-name'       => 'First Name',
                    'last-name'        => 'Last Name',
                    'postcode'         => 'Zip/Postcode',
                    'street-address'   => 'Street address',
                    'state'            => 'State',
                    'select-state'     => 'Select State',
                    'select-country'   => 'Select Country',
                    'save-address'     => 'Save this address',
                    'shipping-address' => 'Shipping Address',
                    'telephone'        => 'Telephone',
                ],
            ],

            'coupon' => [
                'applied'         => 'Coupon Applied',
                'applied-coupon'  => 'Applied Coupon',
                'apply'           => 'Apply Coupon',
                'apply-issue'     => 'Coupon code can\'t be applied.',
                'button-title'    => 'Apply',
                'code'            => 'Coupon Code',
                'discount'        => 'Coupon Discount',
                'enter-your-code' => 'Enter your code',
                'remove'          => 'Remove Coupon',
                'sub-total'       => 'Subtotal',
                'subtotal'        => 'Subtotal',
            ],

            'index' => [
                'checkout' => 'Checkout',
                'home'     => 'Home',
            ],

            'payment' => [
                'payment-method' => 'Payment Method',
            ],

            'shipping' => [
                'shipping-method' => 'Shipping Method',
            ],

            'summary' => [
                'cart-summary'     => 'Cart Summary',
                'delivery-charges' => 'Delivery Charges',
                'discount-amount'  => 'Discount Amount',
                'grand-total'      => 'Grand Total',
                'place-order'      => 'Place Order',
                'processing'       => 'Processing',
                'sub-total'        => 'Subtotal',
                'tax'              => 'Tax',
            ],
        ],
    ],

    'home' => [
        'index' => [
            'offer'               => 'Get UPTO 40% OFF on your 1st order SHOP NOW',
            'resend-verify-email' => 'Resend Verification Email',
            'verify-email'        => 'Verify your email account',
        ],
    ],

    'errors' => [
        'go-to-home'   => 'Go To Home',

        '404' => [
            'description' => 'Oops! The page you\'re looking for is on vacation. It seems we couldn\'t find what you were searching for.',
            'title'       => '404 Page Not Found',
        ],

        '401' => [
            'description' => 'Oops! Looks like you\'re not allowed to access this page. It seems you\'re missing the necessary credentials.',
            'title'       => '401 Unauthorized',
        ],

        '403' => [
            'description' => 'Oops! This page is off-limits. It appears you don\'t have the required permissions to view this content.',
            'title'       => '403 Forbidden',
        ],

        '500' => [
            'description' => 'Oops! Something went wrong. It seems we\'re having trouble loading the page you\'re looking for.',
            'title'       => '500 Internal Server Error',
        ],

        '503' => [
            'description' => 'Oops! Looks like we\'re temporarily down for maintenance. Please check back in a bit.',
            'title'       => '503 Service Unavailable',
        ],
    ],

    'layouts' => [
        'address'               => 'Address',
        'downloadable-products' => 'Downloadable Products',
        'my-account'            => 'My Account',
        'orders'                => 'Orders',
        'profile'               => 'Profile',
        'reviews'               => 'Reviews',
        'wishlist'              => 'Wishlist',
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
                'description' => 'Your account has now been created successfully and you can login using your email address and password credentials. Upon logging in, you will be able to access other services including reviewing past orders, wishlists and editing your account information.',
                'greeting'    => 'Welcome and thank you for registering with us!',
                'subject'     => 'New Customer Registration',
                'sign-in'     => 'Sign in',
            ],

            'forgot-password' => [
                'description'    => 'You are receiving this email because we received a password reset request for your account.',
                'greeting'       => 'Forgot Password!',
                'reset-password' => 'Reset Password',
                'subject'        => 'Reset Password Email',
            ],

            'update-password' => [
                'description' => 'You are receiving this email because you have updated your password.',
                'greeting'    => 'Password Updated!',
                'subject'     => 'Password Updated',
            ],

            'verification' => [
                'description'  => 'Please click the button below to verify your email address.',
                'greeting'     => 'Welcome!',
                'subject'      => 'Account Verification Email',
                'verify-email' => 'Verify Email Address',
            ],

            'commented' => [
                'description' => 'Note Is - :note',
                'subject'     => 'New comment Added',
            ],

            'subscribed' => [
                'description' => 'Congratulations and welcome to our newsletter community! We\'re excited to have you on board and keep you updated with the latest news, trends, and exclusive offers.',
                'greeting'    => 'Welcome to our newsletter!',
                'subject'     => 'You! Subscribe to Our Newsletter',
                'unsubscribe' => 'Unsubscribe',
            ],
        ],

        'orders' => [
            'created' => [
                'greeting' => 'Thanks for your Order :order_id placed on :created_at',
                'subject'  => 'New Order Confirmation',
                'summary'  => 'Summary of Order',
                'title'    => 'Order Confirmation!',
            ],

            'invoiced' => [
                'greeting' => 'Your invoice #:invoice_id for Order :order_id created on :created_at',
                'subject'  => 'New Invoice Confirmation',
                'summary'  => 'Summary of Invoice',
                'title'    => 'Invoice Confirmation!',
            ],

            'shipped' => [
                'greeting' => 'Your order :order_id placed on :created_at has been shipped',
                'subject'  => 'New Shipment Confirmation',
                'summary'  => 'Summary of Shipment',
                'title'    => 'Order Shipped!',
            ],

            'refunded' => [
                'greeting' => 'Refund has been initiated for the :order_id placed on :created_at',
                'subject'  => 'New Refund Confirmation',
                'summary'  => 'Summary of Refund',
                'title'    => 'Order Refunded!',
            ],

            'canceled' => [
                'greeting' => 'Your Order :order_id placed on :created_at has been canceled',
                'subject'  => 'New Order Canceled',
                'summary'  => 'Summary of Order',
                'title'    => 'Order Canceled!',
            ],

            'commented' => [
                'subject' => 'New comment Added',
                'title'   => 'New comment added to your order :order_id placed on :created_at',
            ],

            'billing-address'   => 'Billing Address',
            'contact'           => 'Contact',
            'carrier'           => 'Carrier',
            'discount'          => 'Discount',
            'grand-total'       => 'Grand Total',
            'name'              => 'Name',
            'payment'           => 'Payment',
            'price'             => 'Price',
            'qty'               => 'Qty',
            'shipping-address'  => 'Shipping Address',
            'shipping'          => 'Shipping',
            'sku'               => 'SKU',
            'subtotal'          => 'Subtotal',
            'shipping-handling' => 'Shipping Handling',
            'tracking-number'   => 'Tracking Number : :tracking_number',
            'tax'               => 'Tax',
        ],
    ],
];
