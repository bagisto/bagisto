<?php

return [
    'customers' => [
        'forgot-password' => [
            'back'                 => 'Back to sign In ?',
            'bagisto'              => 'Bagisto',
            'email'                => 'Email',
            'email-not-exist'      => 'We cannot find a user with that email address.',
            'footer'               => '© Copyright 2010 - :current_year, Webkul Software (Registered in India). All rights reserved.',
            'forgot-password-text' => 'If you forgot your password, recover it by entering your email address.',
            'page-title'           => 'Forgot your password ?',
            'reset-link-sent'      => 'We have e-mailed your reset password link.',
            'sign-in-button'       => 'Sign In',
            'submit'               => 'Reset Password',
            'title'                => 'Recover Password',
        ],

        'reset-password' => [
            'back-link-title'  => 'Back to Sign In',
            'bagisto'          => 'Bagisto',
            'confirm-password' => 'Confirm Password',
            'email'            => 'Registered Email',
            'footer'           => '© Copyright 2010 - :current_year, Webkul Software (Registered in India). All rights reserved.',
            'password'         => 'Password',
            'submit-btn-title' => 'Reset Password',
            'title'            => 'Reset Password',
        ],

        'login-form' => [
            'bagisto'             => 'Bagisto',
            'button-title'        => 'Sign In',
            'create-your-account' => 'Create your account',
            'email'               => 'Email',
            'footer'              => '© Copyright 2010 - :current_year, Webkul Software (Registered in India). All rights reserved.',
            'forgot-pass'         => 'Forgot Password?',
            'form-login-text'     => 'If you have an account, sign in with your email address.',
            'invalid-credentials' => 'Please check your credentials and try again.',
            'new-customer'        => 'New customer?',
            'not-activated'       => 'Your activation seeks admin approval',
            'page-title'          => 'Customer Login',
            'password'            => 'Password',
            'show-password'       => 'Show Password',
            'title'               => 'Sign In',
            'verify-first'        => 'Verify your email account first.',
        ],

        'signup-form' => [
            'account-exists'              => 'Already have an account ?',
            'bagisto'                     => 'Bagisto',
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
            'success'                     => 'Account created successfully.',
            'success-verify'              => 'Account created successfully, an e-mail has been sent for verification.',
            'success-verify-email-unsent' => 'Account created successfully, but verification e-mail unsent.',
            'verification-not-sent'       => 'Error! Problem in sending verification email, please try again later.',
            'verification-sent'           => 'Verification email sent',
            'verified'                    => 'Your account has been verified, try to login now.',
            'verify-failed'               => 'We cannot verify your mail account.',
        ],

        'account' => [
            'home' => 'Home',

            'profile'   => [
                'confirm-password'        => 'Confirm Password',
                'current-password'        => 'Current Password',
                'delete'                  => 'Delete',
                'delete-failed'           => 'Error encountered while deleting customer.',
                'delete-profile'          => 'Delete Profile',
                'delete-success'          => 'Customer deleted successfully',
                'dob'                     => 'Date of Birth',
                'edit'                    => 'Edit',
                'edit-profile'            => 'Edit Profile',
                'edit-success'            => 'Profile Updated Successfully',
                'email'                   => 'Email',
                'enter-password'          => 'Enter Your password',
                'female'                  => 'Female',
                'first-name'              => 'First Name',
                'gender'                  => 'Gender',
                'last-name'               => 'Last Name',
                'male'                    => 'Male',
                'new-password'            => 'New Password',
                'order-pending'           => 'Cannot delete customer account because some Order(s) are pending or processing state.',
                'other'                   => 'Other',
                'phone'                   => 'Phone',
                'save'                    => 'Save',
                'select-gender'           => 'Select Gender',
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
                'company-name'     => 'Company Name',
                'country'          => 'Country',
                'create-success'   => 'Address have been successfully added.',
                'default-address'  => 'Default Address',
                'default-delete'   => 'Default address cannot be changed.',
                'delete'           => 'Delete',
                'delete-success'   => 'Address successfully deleted',
                'edit'             => 'Edit',
                'edit-address'     => 'Edit Address',
                'edit-success'     => 'Address updated successfully.',
                'empty-address'    => 'You have not added an address to your account yet.',
                'first-name'       => 'First Name',
                'last-name'        => 'Last Name',
                'phone'            => 'Phone',
                'post-code'        => 'Post Code',
                'save'             => 'Save',
                'security-warning' => 'Suspicious activity found!!!',
                'select-country'   => 'Select Country',
                'set-as-default'   => 'Set as Default',
                'state'            => 'State',
                'street-address'   => 'Street Address',
                'title'            => 'Address',
                'vat-id'           => 'Vat ID',
            ],

            'orders' => [
                'action'      => 'Action',
                'action-view' => 'View',
                'empty-order' => 'You have not ordered any product yet',
                'order'       => 'Order',
                'order-date'  => 'Order Date',
                'order-id'    => 'Order ID',
                'title'       => 'Orders',
                'total'       => 'Total',

                'status' => [
                    'title' => 'Status',

                    'options' => [
                        'canceled'        => 'Canceled',
                        'closed'          => 'Closed',
                        'completed'       => 'Completed',
                        'fraud'           => 'Fraud',
                        'pending'         => 'Pending',
                        'pending-payment' => 'Pending Payment',
                        'processing'      => 'Processing',
                    ],
                ],

                'view' => [
                    'billing-address'    => 'Billing Address',
                    'cancel-btn-title'   => 'Cancel',
                    'cancel-confirm-msg' => 'Are you sure you want to cancel this order ?',
                    'cancel-error'       => 'Your order can not be canceled.',
                    'cancel-success'     => 'Your order has been canceled',
                    'page-title'         => 'Order #:order_id',
                    'payment-method'     => 'Payment Method',
                    'shipping-address'   => 'Shipping Address',
                    'shipping-method'    => 'Shipping Method',
                    'title'              => 'View',
                    'total'              => 'Total',

                    'information' => [
                        'discount'          => 'Discount',
                        'grand-total'       => 'Grand Total',
                        'info'              => 'Information',
                        'item-canceled'     => 'Canceled (:qty_canceled)',
                        'item-invoice'      => 'Invoiced (:qty_invoiced)',
                        'item-ordered'      => 'Ordered (:qty_ordered)',
                        'item-refunded'     => 'Refunded (:qty_refunded)',
                        'item-shipped'      => 'shipped (:qty_shipped)',
                        'item-status'       => 'Item Status',
                        'placed-on'         => 'Placed On',
                        'price'             => 'Price',
                        'product-name'      => 'Name',
                        'shipping-handling' => 'Shipping & Handling',
                        'sku'               => 'SKU',
                        'subtotal'          => 'Subtotal',
                        'tax'               => 'Tax',
                        'tax-amount'        => 'Tax Amount',
                        'tax-percent'       => 'Tax Percent',
                        'total-due'         => 'Total Due',
                        'total-paid'        => 'Total Paid',
                        'total-refunded'    => 'Total Refunded',
                    ],

                    'invoices' => [
                        'discount'           => 'Discount',
                        'grand-total'        => 'Grand Total',
                        'individual-invoice' => 'Invoice #:invoice_id',
                        'invoices'           => 'Invoices',
                        'price'              => 'Price',
                        'print'              => 'Print',
                        'product-name'       => 'Name',
                        'products-ordered'   => 'Products Ordered',
                        'qty'                => 'Qty',
                        'shipping-handling'  => 'Shipping & Handling',
                        'sku'                => 'SKU',
                        'subtotal'           => 'Subtotal',
                        'tax'                => 'Tax',
                        'tax-amount'         => 'Tax Amount',
                    ],

                    'shipments' => [
                        'individual-shipment' => 'Shipment #:shipment_id',
                        'product-name'        => 'Name',
                        'qty'                 => 'Qty',
                        'shipments'           => 'Shipments',
                        'sku'                 => 'SKU',
                        'subtotal'            => 'Subtotal',
                        'tracking-number'     => 'Tracking Number',
                    ],

                    'refunds' => [
                        'adjustment-fee'    => 'Adjustment Fee',
                        'adjustment-refund' => 'Adjustment Refund',
                        'discount'          => 'Discount',
                        'grand-total'       => 'Grand Total',
                        'individual-refund' => 'Refund #:refund_id',
                        'no-result-found'   => 'We could not find any records.',
                        'price'             => 'Price',
                        'product-name'      => 'Name',
                        'qty'               => 'Qty',
                        'refunds'           => 'Refunds',
                        'shipping-handling' => 'Shipping & Handling',
                        'sku'               => 'SKU',
                        'subtotal'          => 'Subtotal',
                        'tax'               => 'Tax',
                        'tax-amount'        => 'Tax Amount',
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

            'downloadable-products' => [
                'date'                => 'Date',
                'download-error'      => 'Download link has been expired.',
                'empty-product'       => 'You don’t have a product to download',
                'name'                => 'Downloadable Products',
                'orderId'             => 'Order Id',
                'payment-error'       => 'Payment has not been done for this download.',
                'records-found'       => 'Record(s) found',
                'remaining-downloads' => 'Remaining Downloads',
                'status'              => 'Status',
                'title'               => 'Title',
            ],

            'wishlist' => [
                'color'              => 'Color',
                'delete-all'         => 'Delete All',
                'empty'              => 'No products were added to the wishlist page.',
                'move-to-cart'       => 'Move To Cart',
                'moved'              => 'Item successfully moved To cart',
                'moved-success'      => 'Item Successfully Moved to Cart',
                'page-title'         => 'Wishlist',
                'product-removed'    => 'Product Is No More Available As Removed By Admin',
                'profile'            => 'Profile',
                'remove'             => 'Remove',
                'remove-all-success' => 'All the items from your wishlist have been removed',
                'remove-fail'        => 'Item Cannot Be Removed From Wishlist',
                'removed'            => 'Item Successfully Removed From Wishlist',
                'see-details'        => 'See Details',
                'success'            => 'Item Successfully Added To Wishlist',
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
            'default-toggle' => 'Default Toggle',
        ],

        'media' => [
            'add-attachments' => 'Add attachments',
        ],

        'layouts' => [
            'header' => [
                'account'           => 'Account',
                'bagisto'           => 'Bagisto',
                'cart'              => 'Cart',
                'compare'           => 'Compare',
                'dropdown-text'     => 'Manage Cart, Orders & Wishlist',
                'logout'            => 'Logout',
                'no-category-found' => 'No category found.',
                'orders'            => 'Orders',
                'profile'           => 'Profile',
                'search'            => 'Search',
                'search-text'       => 'Search products here',
                'sign-in'           => 'Sign In',
                'sign-up'           => 'Sign Up',
                'title'             => 'Account',
                'welcome'           => 'Welcome',
                'welcome-guest'     => 'Welcome Guest',
                'wishlist'          => 'Wishlist',
            ],

            'footer' => [
                'about-us'               => 'About Us',
                'contact-us'             => 'Contact Us',
                'currency'               => 'Currency',
                'customer-service'       => 'Customer Service',
                'email'                  => 'Email',
                'footer-text'            => '© Copyright 2010 - :current_year, Webkul Software (Registered in India). All rights reserved.',
                'locale'                 => 'Locale',
                'newsletter-text'        => 'Get Ready for our Fun Newsletter!',
                'order-return'           => 'Order and Returns',
                'payment-policy'         => 'Payment Policy',
                'privacy-cookies-policy' => 'Privacy and Cookies Policy',
                'shipping-policy'        => 'Shipping Policy',
                'subscribe'              => 'Subscribe',
                'subscribe-newsletter'   => 'Subscribe Newsletter',
                'subscribe-stay-touch'   => 'Subscribe to stay in touch.',
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
                    'last-month'        => 'Last Month',
                    'last-six-months'   => 'Last 6 Months',
                    'last-three-months' => 'Last 3 Months',
                    'this-month'        => 'This Month',
                    'this-week'         => 'This Week',
                    'this-year'         => 'This Year',
                    'today'             => 'Today',
                    'yesterday'         => 'Yesterday',
                ],
            ],

            'table' => [
                'actions'              => 'Actions',
                'next-page'            => 'Next Page',
                'no-records-available' => 'No Records Available.',
                'page-navigation'      => 'Page Navigation',
                'page-number'          => 'Page Number',
                'previous-page'        => 'Previous Page',
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
                'add-to-cart'                 => 'Add To Cart',
                'add-to-compare'              => 'Add To Compare',
                'add-to-compare-success'      => 'Item added successfully to compare list.',
                'add-to-wishlist'             => 'Add To Wishlist',
                'already-in-compare'          => 'Item is already added to compare list.',
                'new'                         => 'New',
                'review-description'          => 'Be the first to review this product',
                'sale'                        => 'Sale',
            ],

            'carousel' => [
                'next'     => 'Next',
                'previous' => 'Previous',
                'view-all' => 'View All',
            ],
        ],

        'range-slider' => [
            'max-range' => 'Max Range',
            'min-range' => 'Min Range',
            'range'     => 'Range:',
        ],

        'carousel' => [
            'image-slide' => 'Image Slide',
            'next'        => 'Next',
            'previous'    => 'Previous',
        ],

        'quantity-changer' => [
            'decrease-quantity' => 'Decrease Quantity',
            'increase-quantity' => 'Increase Quantity',
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
                'cancel'           => 'Cancel',
                'comment'          => 'Comment',
                'customer-review'  => 'Customer Reviews',
                'empty-review'     => 'No Review found, be the first to review this product',
                'failed-to-upload' => 'The image failed to upload',
                'load-more'        => 'Load More',
                'name'             => 'Name',
                'rating'           => 'Rating',
                'submit-review'    => 'Submit Review',
                'success'          => 'Review submitted successfully.',
                'title'            => 'Title',
                'translate'        => 'Translate',
                'translating'      => 'Translating...',
                'write-a-review'   => 'Write a Review',
            ],

            'add-to-cart'            => 'Add To Cart',
            'add-to-compare'         => 'Product added in compare.',
            'add-to-wishlist'        => 'Add To Wishlist',
            'additional-information' => 'Additional Information',
            'already-in-compare'     => 'Product is already added in compare.',
            'buy-now'                => 'Buy Now',
            'compare'                => 'Compare',
            'description'            => 'Description',
            'related-product-title'  => 'Related Products',
            'review'                 => 'Reviews',
            'tax-inclusive'          => 'Inclusive of all taxes',
            'up-sell-title'          => 'We found other products you might like!',
        ],

        'type' => [
            'abstract' => [
                'offers' => 'Buy :qty for :price each and save :discount',
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
            'grid' => 'Grid',
            'list' => 'List',
            'show' => 'Show',
        ],

        'view' => [
            'empty'     => 'No products available in this category',
            'load-more' => 'Load More',
        ],
    ],

    'search' => [
        'title'   => 'Search results for : :query',
        'results' => 'Search results',

        'images' => [
            'index' => [
                'only-images-allowed'  => 'Only images (.jpeg, .jpg, .png, ..) are allowed.',
                'search'               => 'Search',
                'size-limit-error'     => 'Size Limit Error',
                'something-went-wrong' => 'Something went wrong, please try again later.',
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
        'remove-all-success' => 'All items removed successfully.',
        'remove-error'       => 'Something went wrong, please try again later.',
        'remove-success'     => 'Item removed successfully.',
        'title'              => 'Product Compare',
    ],

    'checkout' => [
        'success' => [
            'info'          => 'We will email you, your order details and tracking information',
            'order-id-info' => 'Your order id is #:order_id',
            'thanks'        => 'Thank you for your order!',
            'title'         => 'Order successfully placed',
        ],

        'cart' => [
            'continue-to-checkout'      => 'Continue to Checkout',
            'illegal'                   => 'Quantity cannot be lesser than one.',
            'inactive'                  => 'The item has been deactivated and subsequently removed from the cart.',
            'inventory-warning'         => 'The requested quantity is not available, please try again later.',
            'item-add-to-cart'          => 'Item Added Successfully',
            'minimum-order-message'     => 'Minimum order amount is :amount',
            'missing-fields'            => 'Some required fields missing for this product.',
            'missing-options'           => 'Options are missing for this product.',
            'paypal-payment-cancelled'  => 'Paypal payment has been cancelled.',
            'qty-missing'               => 'At least one product should have more than 1 quantity.',
            'return-to-shop'            => 'Return To Shop',
            'rule-applied'              => 'Cart rule applied',
            'select-hourly-duration'    => 'Select a slot duration of one hour.',
            'success-remove'            => 'Item is successfully removed from the cart.',
            'suspended-account-message' => 'Your account has been suspended.',

            'index' => [
                'bagisto'                  => 'Bagisto',
                'cart'                     => 'Cart',
                'continue-shopping'        => 'Continue Shopping',
                'empty-product'            => 'You don’t have a product in your cart.',
                'home'                     => 'Home',
                'items-selected'           => ':count Items Selected',
                'move-to-wishlist'         => 'Move To Wishlist',
                'move-to-wishlist-success' => 'Selected items successfully moved to wishlist.',
                'price'                    => 'Price',
                'product-name'             => 'Product Name',
                'quantity'                 => 'Quantity',
                'quantity-update'          => 'Quantity updated successfully',
                'remove'                   => 'Remove',
                'remove-selected-success'  => 'Selected items successfully removed from cart.',
                'see-details'              => 'See Details',
                'select-all'               => 'Select All',
                'select-cart-item'         => 'Select Cart Item',
                'tax'                      => 'Tax',
                'total'                    => 'Total',
                'update-cart'              => 'Update Cart',
                'view-cart'                => 'View Cart',

                'cross-sell' => [
                    'title' => 'More choices',
                ],
            ],

            'coupon'   => [
                'already-applied' => 'Coupon code already applied.',
                'applied'         => 'Coupon applied',
                'apply'           => 'Apply Coupon',
                'apply-issue'     => 'Coupon code can\'t be applied.',
                'button-title'    => 'Apply',
                'code'            => 'Coupon code',
                'discount'        => 'Coupon Discount',
                'enter-your-code' => 'Enter your code',
                'error'           => 'Something went wrong',
                'invalid'         => 'Coupon code is invalid.',
                'remove'          => 'Remove Coupon',
                'subtotal'        => 'Subtotal',
                'success-apply'   => 'Coupon code applied successfully.',
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
                    'back'                 => 'Back',
                    'billing-address'      => 'Billing Address',
                    'city'                 => 'City',
                    'company-name'         => 'Company Name',
                    'confirm'              => 'Confirm',
                    'country'              => 'Country',
                    'email'                => 'Email',
                    'first-name'           => 'First Name',
                    'last-name'            => 'Last Name',
                    'postcode'             => 'Zip/Postcode',
                    'save'                 => 'Save',
                    'same-billing-address' => 'Address is the same as my billing address',
                    'save-address'         => 'Save this address',
                    'select-country'       => 'Select Country',
                    'select-state'         => 'Select State',
                    'state'                => 'State',
                    'street-address'       => 'Street Address',
                    'telephone'            => 'Telephone',
                ],

                'index' => [
                    'bagisto' => 'Bagisto',
                    'confirm' => 'Confirm',
                ],

                'shipping' => [
                    'add-new-address'  => 'Add new address',
                    'back'             => 'Back',
                    'city'             => 'City',
                    'company-name'     => 'Company Name',
                    'confirm'          => 'Confirm',
                    'country'          => 'Country',
                    'email'            => 'Email',
                    'first-name'       => 'First Name',
                    'last-name'        => 'Last Name',
                    'postcode'         => 'Zip/Postcode',
                    'save'             => 'Save',
                    'save-address'     => 'Save this address',
                    'select-country'   => 'Select Country',
                    'select-state'     => 'Select State',
                    'shipping-address' => 'Shipping Address',
                    'state'            => 'State',
                    'street-address'   => 'Street address',
                    'telephone'        => 'Telephone',
                ],
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
                'price_&_qty'      => ':price × :qty',
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
                'credentials-description' => 'Your account has been created. Your account details are below:',
                'description'             => 'Your account has now been created successfully and you can login using your email address and password credentials. Upon logging in, you will be able to access other services including reviewing past orders, wishlists and editing your account information.',
                'greeting'                => 'Welcome and thank you for registering with us!',
                'password'                => 'Password',
                'sign-in'                 => 'Sign in',
                'subject'                 => 'New Customer Registration',
                'username-email'          => 'Username/Email',
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
            'carrier'           => 'Carrier',
            'contact'           => 'Contact',
            'discount'          => 'Discount',
            'grand-total'       => 'Grand Total',
            'name'              => 'Name',
            'payment'           => 'Payment',
            'price'             => 'Price',
            'qty'               => 'Qty',
            'shipping'          => 'Shipping',
            'shipping-address'  => 'Shipping Address',
            'shipping-handling' => 'Shipping Handling',
            'sku'               => 'SKU',
            'subtotal'          => 'Subtotal',
            'tax'               => 'Tax',
            'tracking-number'   => 'Tracking Number : :tracking_number',
        ],
    ],
];
