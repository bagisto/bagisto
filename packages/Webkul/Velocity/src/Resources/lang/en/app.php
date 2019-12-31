<?php

return [
    'admin' => [
        'system'   => [
            'velocity' => [
                'extension_name' => 'Velocity Theme',
                'settings'  => 'Settings',
                'general'  => 'General',
                'category'  => 'Category',
                'error-module-inactive' => 'Warning: Velocity theme status is inactive',
            ],

            'settings' => [
                'channels'=> [
                    'subscription_bar' => 'Subscription bar content'
                ],
            ],

            'general' => [
                'status' => 'Status',
                'active' => 'Active',
                'inactive' => 'Inactive',
                'featured-product' => 'Featured Product'
            ],
            'category' => [
                'icon-status' => 'Category Icon Status',
                'active' => 'Active',
                'inactive' => 'Inactive',
                'right' => 'Right',
                'left' => 'Left',
                'image-status' => 'Category Image Status',
                'image-height' => 'Image\'s Height [in Pixel]',
                'image-width' => 'Image\'s Width [in Pixel]',
                'image-alignment' => 'Image Alignment',
                'show-tooltip' => 'Show Category\'s Tooltip',
                'sub-category-show' => 'Show Sub Category',
                'all' => 'All',
                'custom' => 'Custom',
                'num-sub-category' => 'Number Of Sub Category',
            ]
        ],
        'layouts' => [
            'velocity' => 'Velocity',
            'header-content' => 'Header Content',
            'cms-pages' => 'CMS Pages',
            'meta-data' => 'Meta Data',
            'category-menu' => 'Category Menu',
        ],
        'contents' => [
            'title' => 'Content Pages List',
            'add-title' => 'Add Content',
            'btn-add-content' => 'Add New Content',
            'save-btn-title' => 'Save Content',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'select' => '-- Select --',
            'self' => 'Self',
            'new-tab' => 'New Tab',
            'autocomplete' => '[Autocomplete]',
            'search-hint' => 'Search product here...',
            'no-result-found' => 'No record found.',
            'mass-delete-success' => 'Success: Selected conent(s) deleted successfully.',
            'tab' => [
                'page' => 'Page Setting',
                'content' => 'Content Setting',
                'meta_content' => 'Meta Data',
            ],
            'page' => [
                'title' => 'Title',
                'position' => 'Position',
                'status' => 'Status',
            ],
            'content' => [
                'content-type' => 'Content Type',
                'custom-title' => 'Custom Title',
                'custom-heading' => 'Custom Heading',
                'page-link' => 'Page Link [e.g. http://example.com/../../]',
                'link-target' => 'Page Link Target',
                'catalog-type' => 'Product Catalog Type',
                'custom-product' => 'Store Products',
                'static-description' => 'Content Description',
            ],
            'datagrid' => [
                'id' => 'Content Id',
                'title' => 'Title',
                'position' => 'Position',
                'status' => 'Status',
                'content-type' => 'Content Type',
            ]
        ],
        'meta-data' => [
            'title' => 'Velocity meta data',
            'home-page-content' => 'Home Page Content',
            'footer-left-content' => 'Footer Left Content',
            'subscription-content' => 'Subscription bar Content',
            'footer-left-raw-content' => 'We love to craft softwares and solve the real world problems with the binaries. We are highly committed to our goals. We invest our resources to create world class easy to use softwares and applications for the enterprise business with the top notch, on the edge technology expertise.',
            'footer-middle-content' => 'Footer Middle Content',
            'update-meta-data' => 'Update Meta Data',
        ],
        'category' => [
            'title' => 'Category Menu List',
            'add-title' => 'Add Menu Content',
            'edit-title' => 'Edit Menu Content',
            'btn-add-category' => 'Add Category Content',
            'save-btn-title' => 'Save Menu',
            'datagrid' => [
                'category-id' => 'Category Id',
                'category-name' => 'Category Name',
                'category-icon' => 'Category Icon',
                'category-status' => 'Status',
            ],
            'tab' => [
                'general' => 'General',
            ],
            'select' => '-- Select --',
            'select-category' => 'Choose Category',
            'icon-class' => 'Icon Class',
            'tooltip-content' => 'Tooltip Content',
            'status' => 'Status',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'mass-delete-success' => 'Success: Selected categories menu deleted successfully.',
        ]
    ],

    'home' => [
        'page-title' => 'Velocity-Home',
        'add-to-cart' => 'Add To Cart',
    ],

    'header' => [
        'title' => 'Account',
        'welcome-message' => 'Welcome, :customer_name',
        'dropdown-text' => 'Manage Cart, Orders & Wishlist',
        'sign-in' => 'Sign In',
        'sign-up' => 'Sign Up',
        'account' => 'Account',
        'cart' => 'Cart',
        'profile' => 'Profile',
        'wishlist' => 'Wishlist',
        'cart' => 'Cart',
        'logout' => 'Logout',
        'search-text' => 'Search products here',
        'all-categories' => 'All Categories'
    ],

    'menu-navbar' => [
        'text-category' => 'Shop By Category',
        'text-more' => 'More',
    ],

    'minicart' => [
        'view-cart' => 'View Cart',
        'checkout' => 'Checkout',
        'cart' => 'Cart',
        'zero' => '0'
    ],

    'checkout' => [
        'checkout' => 'Checkout',
        'qty' => 'Qty',
        'cart' => [
            'cart-subtotal' => 'Cart Subtotal',
            'view-cart' => 'View Cart',
            'cart-summary' => 'Cart Summary',
        ],
        'proceed' => 'Proceed to checkout',
        'sub-total' => 'Sub Total',
        'items' => 'Items',
        'qty' => 'Qty',
        'subtotal' => 'Subtotal',
    ],

    'customer' => [
        'login-form' => [
            'customer-login' => 'Customer Login',
            'registered-user' => 'Registered User',
            'form-login-text' => 'If you have an account, sign in with your email address.',
            'new-customer' => 'New Customer',
            'your-email-address' => 'Your email address',
        ],
        'signup-form' => [
            'user-registration' => 'User Registration',
            'login' => 'Login',
            'become-user' => 'Become User',
            'form-sginup-text' => 'If you are new to our store, we glab to have you as member.',
        ],
        'forget-password' => [
            'recover-password' => 'Recover Password',
            'recover-password-text' => 'If you forgot your password, recover it by entering your email address.',
            'forgot-password' => 'Forgot Password',
            'login' => 'Login',

        ]
    ],

    'error' => [
        'page-lost-short' => 'Page lost content',
        'page-lost-description' => "The page you're looking for isn't available. Try to search again or use the Go Back button below.",
        'go-to-home' => 'Go to home'
    ],

    'products' => [
        'short-description' => 'Short Descriptions',
        'more-infomation' => 'More Information',
        'details' => 'Details',
        'customer-rating' => 'Customer Rating',
        'reviews' => 'Reviews',
        'view-all-reviews' => 'View All Reviews',
        'write-your-review' => 'Write Your Review',
        'quick-view' => 'Quick View',
        'recently-viewed' => 'Recently Viewed Products',
        'review-by' => 'Review by',
        'be-first-review' => 'Be the first to write a review',
        'submit-review' => 'Submit Review',
    ],

    'shop' => [
        'gender' => [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ],
        'general' => [
            'update' => 'Update',
            'enter-current-password' => 'Enter your current password',
            'new-password' => 'New password',
            'confirm-new-password' => 'Confirm new password',
            'top-brands' => 'Top Brands',
        ],
    ],
]

?>
